<?php

namespace App\Http\Controllers;

use App\Exceptions\ControllableException;
use App\Http\Requests\SendInvitationRequest;
use App\Models\Club;
use App\Models\User;
use App\Services\InvitationService;
use App\Services\PushService;
use Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

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
     * @return JsonResponse
     * @throws ControllableException
     */
    public function inviteUser(Request $request){
        $validator = \Validator::make($request->all(), [
            'invited_id' => 'required|exists:users,id',
            'club_id' => 'required|exists:clubs,id',
            'meeting_at' => 'required|date_format:d.m.Y H:i:s'
        ]);
        if($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $currentUser = Auth::user();
        $invitation = $this->invitation->invite(
            Auth::user(),
            User::find($request->invited_id),
            Club::find($request->club_id),
            $request->meeting_at
        );
        if ( $invitation ){
            try {
                $this->pushes->sendToUser(
                    $this->request->get('invited_id'),
                    "Приглашение на игру",
                    "Вас пригласил на игру " . $currentUser->surname . ' ' . $currentUser->name,
                    []
                );
            } catch (Throwable $e) {
                dd($e);
            }
            return redirect()->back()->with('success', 'Приглашение отправлено');
        }

        return redirect()->back()->with('error', 'Приглашение не отправлено, произошла ошибка');
    }

    public function invitationAccept(Request $request, $id){
        $invitation = $this->invitation->setStatus(Auth::user(), $id, true);
        try {
            $this->pushes->sendToUser(
                $this->request->get($invitation->inviter->id),
                "Приглашение принято",
                $invitation->inviter->name . " " . $invitation->inviter->surname .
                " принял приглашение на игру",
                []
            );
        } catch (Throwable $e) {
        }
        return back();
    }

    public function invitationReject(Request $request, $id){
        $res = $this->invitation->setStatus(Auth::user(), $id, false);
        return back();
    }

    public function invitationDelete(Request $request, $id){
        $res = $this->invitation->deleteInvitation($id);
        return $this->responder->successResponse($res);
    }

    public function invitationList(){
        $list = $this->invitation->invitationList(Auth::user());
        dd($list);
        return $this->responder->successResponse([
            'invitations' => $list
        ]);
    }
}
