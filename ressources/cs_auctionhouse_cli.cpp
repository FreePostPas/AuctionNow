#include "ObjectMgr.h"
#include "AuctionHouseMgr.h"
#include "ScriptMgr.h"
#include "Language.h"

class auctionhouse_cli_commandscript : public CommandScript
{
public:
	auctionhouse_cli_commandscript() : CommandScript("auctionhouse_cli_commandscript") { }

	ChatCommand* GetCommands() const override
	{
		static ChatCommand ahcliCommandTable[] =
		{
			{ "bid", rbac::RBAC_PERM_COMMAND_AUCTIONNOW, true, &HandleAHCliBidCommand, "", NULL },
			{ "buyout", rbac::RBAC_PERM_COMMAND_AUCTIONNOW, true, &HandleAHCliBuyoutCommand, "", NULL },
			{ NULL, 0, false, NULL, "", NULL }
		};
		static ChatCommand commandTable[] =
		{
			{ "ah_cli", rbac::RBAC_PERM_COMMAND_AUCTIONNOW, true, NULL, "", ahcliCommandTable },
			{ NULL, 0, false, NULL, "", NULL }
		};
		return commandTable;
	}

	//Handler to place bid on an existing auction
	static bool HandleAHCliBidCommand(ChatHandler* handler, const char* args)
	{
		int32 auctionGUID = 0;
		int32 playerGUID = 0;
		uint32 amount = 0;
		AuctionEntry* auction = nullptr;
		Player* bidder = nullptr;

		if (!HandleStandardArgs(handler, args, &auctionGUID, &playerGUID, &amount, auction, bidder))
			return false;

		//Check if offer is at least better than previous
		if (amount <= auction->bid || amount < auction->startbid)
			return false;

		//Check for amount comparing to previous bid
		if ((amount < auction->buyout || auction->buyout == 0) &&
			amount < auction->bid + auction->GetAuctionOutBid())
			return false;

		//Remove player's money
		SQLTransaction trans = CharacterDatabase.BeginTransaction(); // Must be prepared before SendAuctionOutbiddedMail()

		if (amount < auction->buyout || auction->buyout == 0) //Check for value comparing to buyout
		{
			if (auction->bidder > 0)
			{
				if (auction->bidder == bidder->GetGUIDLow())
					bidder->ModifyMoney(-int32(amount - auction->bid));
				else
				{
					// mail to last bidder and return money
					sAuctionMgr->SendAuctionOutbiddedMail(auction, amount, sObjectMgr->GetPlayerByLowGUID(auction->bidder), trans);
					bidder->ModifyMoney(-int32(amount));
				}
			}
			else
				bidder->ModifyMoney(-int32(amount));
		}


		//Update auction
		bidder->UpdateAchievementCriteria(ACHIEVEMENT_CRITERIA_TYPE_HIGHEST_AUCTION_BID, amount);

		PreparedStatement* stmt = CharacterDatabase.GetPreparedStatement(CHAR_UPD_AUCTION_BID);
		stmt->setUInt32(0, auction->bidder);
		stmt->setUInt32(1, auction->bid);
		stmt->setUInt32(2, auction->Id);
		trans->Append(stmt);

		handler->SendSysMessage(LANG_AUCTIONNOW_SUCESS);

		bidder->SaveInventoryAndGoldToDB(trans);
		CharacterDatabase.CommitTransaction(trans);
		return true;
	}

	//Handler to make an instant buy
	static bool HandleAHCliBuyoutCommand(ChatHandler* handler, const char* args)
	{
		int32 auctionGUID = 0;
		int32 playerGUID = 0;
		AuctionEntry* auction = nullptr;
		Player* bidder = nullptr;

		if (!HandleStandardArgs(handler, args, &auctionGUID, &playerGUID, nullptr, auction, bidder))
			return false;

		//Check if offer is at least better than previous
		if (auction->buyout <= auction->bid || auction->buyout < auction->startbid)
			return false;

		//Remove player's money
		SQLTransaction trans = CharacterDatabase.BeginTransaction(); // Must be prepared before SendAuctionOutbiddedMail()

		if (auction->buyout != 0) //Check for value comparing to buyout
		{
			if (auction->bidder > 0)
			{
				if (auction->bidder == bidder->GetGUIDLow())
					bidder->ModifyMoney(-int32(auction->buyout - auction->bid));
				else
				{
					// mail to last bidder and return money
					sAuctionMgr->SendAuctionOutbiddedMail(auction, auction->buyout, sObjectMgr->GetPlayerByLowGUID(auction->bidder), trans);
					bidder->ModifyMoney(-int32(auction->buyout));
				}
			}
			else
				bidder->ModifyMoney(-int32(auction->buyout));
		}
		else
			return false;

		//Update auction
		bidder->UpdateAchievementCriteria(ACHIEVEMENT_CRITERIA_TYPE_HIGHEST_AUCTION_BID, auction->buyout);

		PreparedStatement* stmt = CharacterDatabase.GetPreparedStatement(CHAR_UPD_AUCTION_BID);
		stmt->setUInt32(0, auction->bidder);
		stmt->setUInt32(1, auction->bid);
		stmt->setUInt32(2, auction->Id);
		trans->Append(stmt);

		handler->SendSysMessage(LANG_AUCTIONNOW_SUCESS);
		bidder->SaveInventoryAndGoldToDB(trans);
		CharacterDatabase.CommitTransaction(trans);
		return true;
	}

private:
	static AuctionEntry* GetAuctionByGUID(uint32 auction_guid)
	{
		AuctionHouseObject* AllianceAHobj = sAuctionMgr->GetAuctionsMap(FACTION_MASK_ALLIANCE);
		AuctionHouseObject* HordeAHobj = sAuctionMgr->GetAuctionsMap(FACTION_MASK_HORDE);
		AuctionHouseObject* NeutralAHobj = sAuctionMgr->GetAuctionsMap(8); //Not certain of the argument

		AuctionHouseObject* theAuctionIsHere = NULL;

		if (AllianceAHobj->GetAuction(auction_guid))
			theAuctionIsHere = AllianceAHobj;
		else if (HordeAHobj->GetAuction(auction_guid))
			theAuctionIsHere = HordeAHobj;
		else if (NeutralAHobj->GetAuction(auction_guid))
			theAuctionIsHere = NeutralAHobj;
		else
			return false;

		AuctionEntry* auction = theAuctionIsHere->GetAuction(auction_guid);
		return auction;
	}

	/*
	* Helper function to reduce code
	*      handler, args : inherited from the calling function
	*      auctionGUID   : pointer to store the GUID of the auction
	*      playerGUID    : pointer to store the GUID of the target player
	*      amount        : pointer to store the amount (can be null)
	*      auction       : pointer to the auction
	*      bidder        : pointer to the bidder of the auction
	*/
	static bool HandleStandardArgs(ChatHandler* handler, char const* args, int32* auctionGUID,
		int32* playerGUID, uint32* amount, AuctionEntry* auction,
		Player* bidder)
	{
		// No need to check for the other pointer, if they are null it means we don't have
		// enough memory so we cannot have reached this place. 

		if (!*args)
		{
			handler->SendSysMessage(LANG_AUCTIONNOW_BAD_ARGUMENT);
			handler->SetSentErrorMessage(true);
			return false;
		}

		Tokenizer tokens(std::string(args), ' ');

		if (tokens.size() != (amount != nullptr ? 3 : 2))
			return SendMessage(handler, LANG_AUCTIONNOW_BAD_ARGUMENT);

		std::vector<int32> argsExploded;
		for (auto iter = tokens.begin(); iter != tokens.end(); ++iter)
			argsExploded.push_back(atoi(*iter));

		*auctionGUID = argsExploded[0];
		*playerGUID = argsExploded[1];
		if (amount)
			*amount = argsExploded[2];

		auction = GetAuctionByGUID(*auctionGUID);
		if (!auction)
			return SendMessage(handler, LANG_AUCTIONNOW_NO_AUCTION);

		bidder = sObjectMgr->GetPlayerByLowGUID(*playerGUID);
		if (!bidder)
			return false;

		if (auction->owner == bidder->GetGUIDLow())
			return SendMessage(handler, LANG_AUCTIONNOW_BIDDER_IS_OWNER);

		if (!bidder->HasEnoughMoney(auction->buyout))
			return SendMessage(handler, LANG_AUCTIONNOW_MISS_MONEY);

		return true;
	}

	static bool SendMessage(ChatHandler* handler, uint32 messageID)
	{
		handler->SendSysMessage(messageID);
		handler->SetSentErrorMessage(true);
		return false;
	}
};

void AddSC_auctionhouse_cli_commandscript()
{
	new auctionhouse_cli_commandscript();
}
