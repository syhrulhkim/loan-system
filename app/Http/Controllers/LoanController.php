<?php

namespace App\Http\Controllers;

use App\Loan;
use App\Models\User;
use App\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LoanController extends Controller
{

    public function view()
    {
        $authuser = Auth::user();
        $user = User::where('id', $authuser->id)->first();
        $loanList = Payment::where('user_id', $user->id)->orderBy('id', 'asc')->get();
        $loanListPay = Payment::where('user_id', $user->id)->where('status', 'due')->orderBy('id', 'asc')->first();
        
        // if($loanList->isNotEmpty()){
        //     dd()
        //     if($loanListPay->amount_paid == 0){
        //         $pay = Payment::where('status', 'due')->first();
        //     }
        // } else {
        //     $pay = Payment::where('status', 'due')->first();
        // }

        return view('dashboard', ['pay' => $loanListPay , 'loanList' => $loanList ,'user' => $user]);
    }

    public function insert_data(Request $request)
    {
        $user = Auth::user();
        $userDB = User::find($user->id);
        $amountLoan = $request->amount;
        $duration = $request->months;
        $interest = 3;

        $currentDateTime = now();
        $startDate = $currentDateTime->format('d-m-Y');
        $endDate = $currentDateTime->addMonths($request->months)->format('d-m-Y');

        $totalAmountLoan = intval($amountLoan + ($amountLoan * ($interest/100)));

        $loan = Loan::orderBy('id','desc')->first();
        if ($loan === null){
            $loanId = 'LOAN' . 0 . 0 . 1;    
        } else {
            $auto_inc_prd = $loan->id + 1;
            $loanId = 'LOAN' . 0 . 0 . $auto_inc_prd;    
        }

        $totalPayMonths = $totalAmountLoan / $duration;

        $payment = Loan::where('user_id', $user->id)->where('status', null)->orderBy('id','desc')->first();
        // dd($payment);
        // Initialize auto_inc_prd based on the presence of previous payments
        $auto_inc_prd = $payment ? $payment->id + 1 : 1;

        for ($i = 1; $i <= $duration; $i++) {
            $paymentId = 'PAY' . str_pad($auto_inc_prd, 3, '0', STR_PAD_LEFT) . $user->id;
        
            if ($payment == null) {
                $startDatePay = $startDate;
            } else {
                // Use the endDatePay of the previous array as the new startDatePay
                $startDatePay = $payment->payment_endDue;
            }

            // Calculate the endDatePay based on the new startDatePay
            $endDatePay = Carbon::createFromFormat('d-m-Y', $startDatePay)->addMonths(1)->format('d-m-Y');
            
        
            if ($i == 1){
                Payment::create([
                    'user_id' => $user->id,
                    'payment_id' => $paymentId,
                    'payment_startDue' => $startDatePay,
                    'payment_endDue' => $endDatePay,
                    'date' => $currentDateTime,
                    'amount_loan' => round($totalPayMonths, 2),
                    'status' => 'due',
                ]);
            } else {
                Payment::create([
                    'user_id' => $user->id,
                    'payment_id' => $paymentId,
                    'payment_startDue' => $startDatePay,
                    'payment_endDue' => $endDatePay,
                    'date' => $currentDateTime,
                    'amount_loan' => round($totalPayMonths, 2),
                    'status' => 'ongoing',
                ]);
            }
            

            // Update startDate for the next iteration
            $startDate = $endDatePay;
        
            // Increment auto_inc_prd for the next iteration
            $auto_inc_prd++;
        }        
        
        // Create loan information
        Loan::create([
            'user_id' => $user->id,
            'loan_id' => $loanId,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'months' => $duration,
            'interest' => $interest,
            'amount_loan' => $amountLoan,
            'amount_loan_interest' => $totalAmountLoan,
            'status' => 'ongoing',
        ]);

        if ($user) {
            $user->update([
                "status" => "loan",
                "updated_at" => date("Y-m-d H:i:s\ "),
            ]);
        }

        $userLoan = Loan::where('user_id', $user->id)->get();
        return redirect('/dashboard')
            ->with('add-success', 'Loan Successfully Created')
            ->with('userLoan', $userLoan);
    }

    public function payloan($id)
    {   
        $authuser = Auth::user();
        $user = User::where('id', $authuser->id)->first();
        $loan = Loan::where('user_id', $user->id)->where('status', 'ongoing')->first();
        $loanList = Payment::where('user_id', $user->id)->orderBy('id', 'asc')->get();
        $previousPayment = Payment::where('user_id', $user->id)->where('status', 'ongoing')->where('id',$id - 1)->first();
        $payment = Payment::where('user_id', $user->id)->where('id',$id)->first();
        $nextPayment = Payment::where('user_id', $user->id)->where('status', 'ongoing')->where('id',$id + 1)->first();

        // if no more payment change status to completed
        if($nextPayment == null){
            $loan->update([
                "status" => 'completed',
            ]);
        }

        // update payment
        if ($payment->status == 'due'){
            $payment->update([
                "amount_paid" => $payment->amount_loan,
                "status" => "paid",
                "date_paid" => now(),
            ]);
            // update next payment to due
            if($nextPayment == null){
                $loan->update([
                    "status" => 'completed',
                ]);
            }   else {
                $nextPayment->update([
                    "status" => "due",
                ]);
            }
        } else {
            // do nothing
        }
        
        // add amount paid at loan table
        if ($loan->amount_paid == null){
            $loan->update([
                "amount_paid" => $payment->amount_loan,
                "date_paid" => now(),
            ]);
        } else {
            $loan->update([
                "amount_paid" => $payment->amount_loan + $loan->amount_paid,
                "date_paid" => now(),
            ]);
        }

        // deduct user saving each payment
        $user->update([
            "saving" => $user->saving - $payment->amount_loan,
            "date_paid" => now(),
        ]);

        if($loan->status == 'done') {
            $user->update([
                "status" => "available",
            ]);
        } elseif($nextPayment != null) {
            $user->update([
                "status" => "loan",
            ]);
        } else {
            $user->update([
                "status" => "rejected",
            ]);
        }

        // dd($payment);
        // $loanList = Payment::where('user_id', $user->id)->orderBy('id', 'asc')->get();
        return redirect()->action([LoanController::class, 'view']);
        // return view('dashboard', ['pay' => $nextPayment ,'loanList' => $loanList , 'user' => $user]);
    }
}
