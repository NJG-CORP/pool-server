<?php
namespace App\Services;

use App\Models\Club;
use App\Models\Invitation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder;

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
        $q = Invitation::
            with(['inviter.avatar', 'inviter.receivedRatings', 'club.location'])
            ->where('invited_id', $user->id)
            ->where(function(\Illuminate\Database\Eloquent\Builder $q){
                $q->where('accepted', null)
                    ->orWhere('accepted', 1);
            });
        return $q
            ->get()
            ->map(function (Invitation $e){
                $e->inviter->setAppends(['calculated_rating']);
                return $e;
            });
    }

    /**
     * @param $user
     * @param $invId
     * @param $accepted
     * @return bool|Invitation
     */
    public function setStatus($user, $invId, $accepted){
        $invitation = Invitation::find($invId);
        if ( $invitation && $invitation->invited->id === $user->id ){
            $invitation->accepted = $accepted;
            $invitation->save();
            return $invitation;
        }
        return false;
    }

    /**
     * @param $id
     * @return boolean
     */
    public function deleteInvitation($id){
        return Invitation::findOrFail($id)->delete();
    }
}
