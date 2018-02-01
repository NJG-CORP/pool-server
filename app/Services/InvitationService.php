<?php
namespace App\Services;

use App\Models\Club;
use App\Models\Invitation;
use App\Models\User;
use Carbon\Carbon;

class InvitationService
{
    /**
     * @param User $inviter
     * @param User $invited
     * @param Club $club
     * @param $meetingAt
     * @return Invitation
     */
    public function invite(User $inviter, User $invited, Club $club, $meetingAt){
        $invitation = Invitation::create([
            'inviter_id' => $inviter->id,
            'invited_id' => $invited->id,
            'club_id' => $club->id,
            'meeting_at' => new Carbon($meetingAt)
        ]);
        return $invitation;
    }

    public function invitationList(User $user){
        return Invitation::where('inviter_id', $user->id)
            ->orWhere('invited_id', $user->id)->get();
    }
}
