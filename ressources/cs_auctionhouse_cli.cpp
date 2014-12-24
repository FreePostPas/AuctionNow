/*
	* Do not use without written autorisation from Adrien Albaladejo/Freegos/Ures (adrien.albaladejo@gmail.com)
	* Two subcommands : 
	* * auctionhouse_cli bid #auction_guid #character_guid #amount
	* * auctionhouse_cli buyout #auction_guid #character_guid
*/

/* ScriptData
Name: auctionhouse_cli_commandscript
Comment: Custom command use for AuctionNow via SOAP
Category: commandscripts
EndScriptData */

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
			{ "auctionhouse_cli", rbac::RBAC_PERM_COMMAND_AUCTIONNOW, true, NULL, "", ahcliCommandTable },
			{ NULL, 0, false, NULL, "", NULL }
		};
		return commandTable;
	}

	//Handler to place bid on an existing auction
	static bool HandleAHCliBidCommand(ChatHandler* handler, const char* args)
	{
		if (!*args)
		{
			handler->SendSysMessage(LANG_AUCTIONNOW_BAD_ARGUMENT);
			handler->SetSentErrorMessage(true);
			return false;
		}

		Tokenizer tokens(std::string(args), ' ');

		std::vector<int32> args_exploded;
		for (Tokenizer::const_iterator iter = tokens.begin(); iter != tokens.end(); ++iter)
			args_exploded.push_back(atoi(*iter));
		
		if (tokens.size() != 3)
		{
			handler->SendSysMessage(LANG_AUCTIONNOW_BAD_ARGUMENT);
			handler->SetSentErrorMessage(true);
			return false;
		}

		const int auction_guid = args_exploded[0];
		const int player_guid = args_exploded[1];
		const uint32 amount = args_exploded[2];

		if (!auction_guid || !player_guid || !amount)
		{
			handler->SendSysMessage(LANG_AUCTIONNOW_BAD_ARGUMENT);
			handler->SetSentErrorMessage(true);
			return false;
		}

		AuctionEntry* auction = GetAuctionByGUID(auction_guid);

		if (!auction)
		{
			//Auction do not exist
			handler->SendSysMessage(LANG_AUCTIONNOW_NO_AUCTION);
			handler->SetSentErrorMessage(true);
			return false;
		}
		
		Player* bidder = sObjectMgr->GetPlayerByLowGUID(player_guid);

		if (!bidder)
			return false;

		//Check if bidder is different from owner
		if (auction->owner == bidder->GetGUIDLow())
		{
			handler->SendSysMessage(LANG_AUCTIONNOW_BIDDER_IS_OWNER);
			handler->SetSentErrorMessage(true);
			return false;
		}
			
		//Check if bidder has enough money
		if (!bidder->HasEnoughMoney(amount))
		{
			handler->SendSysMessage(LANG_AUCTIONNOW_MISS_MONEY);
			handler->SetSentErrorMessage(true);
			return false;
		}

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
		if (!*args)
		{
			handler->SendSysMessage(LANG_AUCTIONNOW_BAD_ARGUMENT);
			handler->SetSentErrorMessage(true);
			return false;
		}

		Tokenizer tokens(std::string(args), ' ');

		std::vector<int> args_exploded;
		for (Tokenizer::const_iterator iter = tokens.begin(); iter != tokens.end(); ++iter)
			args_exploded.push_back(atoi(*iter));

		if (tokens.size() != 2)
		{
			handler->SendSysMessage(LANG_AUCTIONNOW_BAD_ARGUMENT);
			handler->SetSentErrorMessage(true);
			return false;
		}

		const int auction_guid = args_exploded[0];
		const int player_guid = args_exploded[1];

		if (!auction_guid || !player_guid)
		{
			handler->SendSysMessage(LANG_AUCTIONNOW_BAD_ARGUMENT);
			handler->SetSentErrorMessage(true);
			return false;
		}
		
		AuctionEntry* auction = GetAuctionByGUID(auction_guid);

		if (!auction)
		{
			//Auction do not exist
			handler->SendSysMessage(LANG_AUCTIONNOW_NO_AUCTION);
			handler->SetSentErrorMessage(true);
			return false;
		}

		Player* bidder = sObjectMgr->GetPlayerByLowGUID(player_guid);

		if (!bidder)
			return false;

		//Check if bidder is different from owner
		if (auction->owner == bidder->GetGUIDLow())
		{
			handler->SendSysMessage(LANG_AUCTIONNOW_BIDDER_IS_OWNER);
			handler->SetSentErrorMessage(true);
			return false;
		}

		//Check if bidder has enough money
		if (!bidder->HasEnoughMoney(auction->buyout))
		{
			handler->SendSysMessage(LANG_AUCTIONNOW_MISS_MONEY);
			handler->SetSentErrorMessage(true);
			return false;
		}

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
};

void AddSC_auctionhouse_cli_commandscript()
{
	new auctionhouse_cli_commandscript();
}