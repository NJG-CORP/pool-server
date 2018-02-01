<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\User;
use App\Services\InvitationService;
use Illuminate\Http\Request;

class InvitationController extends Controller
{
    /**
     * @var InvitationService  $invitation
     */
    private $invitation;

    public function __construct(Request $request, InvitationService $invitation)
    {
        parent::__construct($request);
        $this->invitation = $invitation;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ControllableException
     */
    public function inviteUser(Request $request){
        $this->validateRequestData([
            'invited_id' => 'required|integer',
            'club_id' => 'required|integer',
            'meeting_at' => 'required'
        ]);
        $invitation = $this->invitation->invite(
            \Auth::user(),
            User::find($this->request->get('invited_at')),
            Club::find($this->request->get('club_id')),
            $this->request->get('meeting_at')
        );
        if ( $invitation ){
            return $this->responder->successResponse([
                'invitation' => $invitation
            ]);
        }
        return $this->responder->errorResponse();
    }

    public function invitationList(){
        $list = $this->invitation->invitationList(\Auth::user());
        return $this->responder->successResponse([
            'invitations' => $list
        ]);
    }
}
