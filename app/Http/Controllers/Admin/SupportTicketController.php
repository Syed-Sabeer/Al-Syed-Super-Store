<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\SupportMessage;
use App\Models\SupportTicket;
use App\Traits\SupportTicketManager;

class SupportTicketController extends Controller
{
    use SupportTicketManager;

    private $pageTitle;

    public function __construct()
    {
        parent::__construct();
        $this->userType = 'admin';
        $this->column = 'admin_id';
        $this->user = auth()->guard('admin')->user();
    }

    public function tickets()
    {
        $this->pageTitle = 'Support Tickets';
        return $this->ticketList();
    }

    public function pendingTicket()
    {
        $this->pageTitle = 'Pending Tickets';
        return $this->ticketList('pending');
    }

    public function closedTicket()
    {
        $this->pageTitle = 'Closed Tickets';
        return $this->ticketList('closed');
    }

    public function answeredTicket()
    {
        $this->pageTitle = 'Answered Tickets';
        return $this->ticketList('answered');
    }

    public function ticketReply($id)
    {
        $ticket = SupportTicket::with('user')->where('id', $id)->firstOrFail();
        $pageTitle = 'Reply Ticket';
        $messages = SupportMessage::with('ticket','admin','attachments')->where('support_ticket_id', $ticket->id)->orderBy('id','desc')->get();
        return view('admin.support.reply', compact('ticket', 'messages', 'pageTitle'));
    }

    private function ticketList($scope = null) {
        $items = SupportTicket::query();
        if($scope) {
            $items->$scope();
        }
        if(request()->user){
            $items->whereHas('user', function($user){
                $user->where('username', request()->user);
            });
        }
        $items = $items->searchable(['name', 'subject', 'ticket'])->orderBy('id', 'desc')->with('user')->paginate(getPaginate());
        $pageTitle = $this->pageTitle;
        return view('admin.support.tickets', compact('items', 'pageTitle'));

    }

    public function ticketDelete($id)
    {
        $message = SupportMessage::findOrFail($id);
        $path = getFilePath('ticket');
        if ($message->attachments()->count() > 0) {
            foreach ($message->attachments as $attachment) {
                fileManager()->removeFile($path.'/'.$attachment->attachment);
                $attachment->delete();
            }
        }
        $message->delete();
        $notify[] = ['success', "Support ticket deleted successfully"];
        return back()->withNotify($notify);

    }

}
