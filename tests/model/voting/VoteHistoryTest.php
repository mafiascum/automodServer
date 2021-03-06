<?php
namespace mafiascum\automodServer\model\voting;

use PHPUnit\Framework\TestCase;
use mafiascum\automodServer\model\voting\PlayerSlot;
use mafiascum\automodServer\model\voting\VoteChange;
use mafiascum\automodServer\model\voting\VoteHistory;

require_once(dirname(__FILE__) . "/../../../model/voting/VoteChange.php");
require_once(dirname(__FILE__) . "/../../../model/voting/VoteHistory.php");
require_once(dirname(__FILE__) . "/../../../model/voting/VoteTarget.php");

class VoteHistoryTest extends TestCase {
	public function testSimpleVoteHistory() {
		$toto = new PlayerSlot('Toto', NULL);
		$kison = new PlayerSlot('Kison', NULL);
		$voteHistory = new VoteHistory(array($toto, $kison));
		$voteHistory->maybeAddFromPost(1, 1, "Kison", "[v]toto[/v]");
		$voteHistory->maybeAddFromPost(2, 2, "Toto", "[b] Not a vote [/b]");
		$voteHistory->maybeAddFromPost(3, 3, "Toto", "[vote]Toto[/vote] I'm scum");
		$voteHistory->maybeAddFromPost(4, 4, "Toto", "[b]Unvote[/b] Just kiddin'");
		$voteHistory->maybeAddFromPost(5, 5, "Kison", "[v]Not Valid[/v]");
		$this->assertEquals(array(
				new VoteChange(1, 1, $kison, VoteTarget::vote($toto), "[v]toto[/v]"),
				new VoteChange(3, 3, $toto, VoteTarget::vote($toto), "[vote]Toto[/vote]"),
				new VoteChange(4, 4, $toto, VoteTarget::unvote(), "[b]Unvote[/b]"),
				new VoteChange(5, 5, $kison, VoteTarget::unrecognized(), "[v]Not Valid[/v]"),
		), $voteHistory->getHistory());
	}
}
