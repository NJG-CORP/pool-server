<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\User;
use App\Services\InvitationService;
use App\Services\PushService;
use Illuminate\Http\Request;

class InvitationController extends Controller
{
    /**
     * @var InvitationService  $invitation
     */
    private $invitation;

    /**
     * @var PushService $pushes
     */
    private $pushes;

    public function __construct(Request $request, InvitationService $invitation, PushService $pushes)
    {
        parent::__construct($request);
        $this->invitation = $invitation;
        $this->pushes = $pushes;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ControllableException
     */
    public function inviteUser(Request $request){
        $this->validateRequestData([
            'invited_id' => 'required|integer',
            'club_id' => 'integer',
            'meeting_at' => 'required'
        ]);

        $currentUser = \Auth::user();
        $invitation = $this->invitation->invite(
            \Auth::user(),
            User::find($this->request->get('invited_id')),
            Club::find($this->request->get('club_id')),
            $this->request->get('meeting_at')
        );
        if ( $invitation ){
            try {
                $this->pushes->sendToUser(
                    $this->request->get('invited_id'),
                    "Приглашение на игру",
                    "Вас пригласил на игру " . $currentUser->surname . ' ' . $currentUser->name,
                    []
                );
            } catch (\Throwable $e){}
            return $this->responder->successResponse([
                'invitation' => $invitation
            ]);
        }

        return $this->responder->errorResponse();
    }

    public function invitationAccept(Request $request, $id){
        $invitation = $this->invitation->setStatus(\Auth::user(), $id, true);
        try {
            $this->pushes->sendToUser(
                $this->request->get($invitation->inviter->id),
                "Приглашение принято",
                $invitation->inviter->name . " " . $invitation->inviter->surname .
                    " принял приглашение на игру",
                []
            );
        } catch (\Throwable $e){}
        return back();
    }

    public function invitationReject(Request $request, $id){
        $res = $this->invitation->setStatus(\Auth::user(), $id, false);
        return back();
    }

    public function invitationDelete(Request $request, $id){
        $res = $this->invitation->deleteInvitation($id);
        return $this->responder->successResponse($res);
    }

    public function invitationList(){
        $list = $this->invitation->invitationList(\Auth::user());
        return $this->responder->successResponse([
            'invitations' => $list
        ]);
    }
}
