<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-black dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="grid-container p-6 text-gray-900 dark:text-gray-100">
                    <div class="item-1">
                        <div class="formbold-event-details">
                            <h5>Loan Details</h5>
                                @if ($loanList->isEmpty())
                                    <div class="" style="color: black; padding:15px 25px; border-top: 1px solid black;">
                                        No Loan List
                                    </div>
                                @else
                                    <div class="list-row">
                                        <div class="list-col">
                                            <div class="">
                                                Amount Due
                                            </div>
                                            <div class="">
                                                Start Due
                                            </div>
                                            <div class="">
                                                End Due
                                            </div>
                                            <div class="">
                                                Date Paid
                                            </div>
                                            <div class="">
                                                Status
                                            </div>
                                            <div class="">
                                                Action
                                            </div>
                                        </div>
                                    </div>
                                    @foreach($loanList as $item)
                                    <div class="list-row">
                                        <div class="list-col">
                                            <div class="">
                                                RM {{ $item->amount_loan }}
                                            </div>
                                            <div class="">
                                                {{ $item->payment_startDue }}
                                            </div>
                                            <div class="">
                                                {{ $item->payment_endDue }}
                                            </div>
                                            <div class="">
                                                @if($item->date_paid == '')
                                                    Unpaid
                                                @else
                                                    {{ \Carbon\Carbon::parse($item->date_paid)->format('d-m-Y') }}
                                                @endif
                                            </div>
                                            <div class="">
                                                @if($item->status == 'due')
                                                    Due
                                                @elseif($item->status == 'paid')
                                                    Completed
                                                @else
                                                    Ongoing
                                                @endif
                                            </div>
                                            <div class="">
                                                @if($item->date_paid == '')
                                                    <a href="{{ route('pay-loan', ['id' => $item->id]) }}" class="pay" disabled>
                                                        PAY
                                                    </a>
                                                @else
                                                    <a class="" disabled>
                                                        Paid
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                @endif
                        </div>
                    </div>
                    <div class="item-2 w-full formbold-main-wrapper">
                        <div class="formbold-form-wrapper">
                            <div class="formbold-input-flex">
                                <div style="border-radius: 5px; border: solid 1px white; width: 100%; padding: 10px 20px 10px 20px; line-height: 18px;">
                                    <h2 for="hours" class="" style="font-size: 16px;"> Saving Amount : RM {{ $user->saving }} </h2>
                                    <h5 for="firstname" class="" style="font-size: 16px;"> Username : {{ $user->name }}</h5>
                                </div>
                            </div>
                            @if ($user->status == "loan")
                            <div class="formbold-input-flex">
                                <div style="border-radius: 5px; border: solid 1px white; width: 100%; padding: 10px 20px 10px 20px; line-height: 18px;">
                                    <h2 class="" style="font-size: 16px;"> Amount Due : RM{{ $pay->amount_loan }} </h2>
                                    <h2 class="" style="font-size: 16px;"> Due Date : {{ $pay->payment_endDue }}</h2>
                                    <h2 class="" style="font-size: 16px;"> Invoice ID : {{ $pay->payment_id }} </h2>
                                </div>
                            </div>
                            <a href="{{ route('pay-loan', ['id' => $pay->id]) }}">
                                <div class="button-to-pay">
                                    <div style="padding: 10px; text-align: center;">
                                        PAY
                                    </div>
                                </div>
                            </a>
                            @else
                            <form action="{{ route('insert-data') }}" method="POST">
                                @csrf
                                @method('patch')
                                <div class="formbold-input-flex">
                                    <div>
                                        <input
                                            type="number"
                                            name="amount"
                                            id="amount"
                                            placeholder="Amount"
                                            class="formbold-form-input"
                                            oninput="limitInput({{ Auth::user()->saving }})"
                                            required
                                        />
                                        <label for="date" class="formbold-form-label"> Loan amount :  </label>
                                        <p id="charLimitMessage" style="color: red;"></p>
                                    </div>
                                    <div>
                                        <input
                                            type="number"
                                            name="months"
                                            id="months"
                                            placeholder="Months"
                                            class="formbold-form-input"
                                            required
                                        />
                                        <label for="hours" class="formbold-form-label"> Duration </label>
                                        <!-- <p id="charLimitMessage" style="color: red;"></p> -->
                                    </div>
                                </div>
                                <button id="myButton" class="formbold-btn" disabled>
                                    Submit Loan
                                </button>
                            </form>
                            @endif

                            @if(session('workingHoursDisplay'))
                                <div class="formbold-form-wrapper">
                                    <div style="padding: 24px 0px 24px 0px;">
                                        <div style="border: 1px solid white; border-radius: 10px; padding: 10px">
                                            <h1 style="font-weight: 900; text-decoration: underline;">
                                                Working Hours List : 
                                            <h1><br>
                                            @foreach(explode(', ', session('workingHoursDisplay')) as $workHour)
                                                {{ $workHour }} <br>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        // Validation for limit loan
        function limitInput(saving) {
            var inputField = document.getElementById("amount");
            var charLimitMessage = document.getElementById("charLimitMessage");
            var myButton = document.getElementById("myButton");
            var savingAmount = saving;

                if (inputField.value > savingAmount) {
                    charLimitMessage.textContent = "Amount loan exceed saving";
                    myButton.disabled = true; // Disable the button
                } else {
                    charLimitMessage.textContent = "";
                    myButton.disabled = false; // Disable the button
                }
        }
    </script>
</x-app-layout>
