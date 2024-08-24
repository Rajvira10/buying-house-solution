<?php

namespace App\Console\Commands;

use App\Models\Customer;
use Xenon\LaravelBDSms\Sender;
use Illuminate\Console\Command;
use App\Services\AccountStatementService;
use Xenon\LaravelBDSms\Provider\BulkSmsBD;

class SendDueSMS extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sms:send-due';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send SMS notifications to customers with due balances';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $sender = Sender::getInstance(); 
        $sender->setProvider(BulkSmsBD::class);
        $sender->setConfig([
            'api_key' => config('sms.providers.Xenon\LaravelBDSms\Provider\BulkSmsBD.api_key'),
            'senderid' => config('sms.providers.Xenon\LaravelBDSms\Provider\BulkSmsBD.senderid'),
        ]);

        $customers = Customer::with('account')->get();

        $due_customers = $customers->filter(function ($customer) {
            $account = $customer->account;
            $accountStatementService = new AccountStatementService();
            $current_balance = $accountStatementService->getCurrentBalance($account->id);

            return $current_balance > 0;
        });


        foreach ($due_customers as $customer) {
            
            $sender->setMobile($customer->primary_contact_no);

            $accountStatementService = new AccountStatementService();
            $current_balance = $accountStatementService->getCurrentBalance($customer->account->id);

            $message = 'Your Due is ' . $current_balance . '. Please pay as soon as possible.';

            $sender->setMessage($message);

            $status = $sender->send();

            $this->info('SMS Status: ' . $status);
        }

        return Command::SUCCESS;
    }
}
