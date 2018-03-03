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
        return Invitation::
            with(['inviter.avatar', 'inviter.receivedRatings', 'club.location'])
            //where('inviter_id', $user->id)
            ->orWhere('invited_id', $user->id)
            ->get()
            ->map(function (Invitation $e){
                $e->inviter->setAppends('calculated_rating');
            });
    }

    public function accept($userId, $invId){
        $invitation = Invitation::find($invId);
        if ( $invitation && $invitation->invited->id === $userId ){
            $invitation->accepted = true;
            $invitation->save();
            return $invitation;
        }
        return false;
    }
}
