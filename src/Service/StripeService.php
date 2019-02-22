<?php
/**
 * Created by PhpStorm.
 * User: tof
 * Date: 15/02/2019
 * Time: 13:38
 */

namespace App\Service;


use App\Entity\Customer;
use App\Entity\Ticket;
use Doctrine\ORM\EntityManager;

class StripeService
{

    public function __construct($privateKey)
    {

        \Stripe\Stripe::setApiKey($privateKey);
    }
    Public function SendCharge($token, Ticket$ticket)
    {

        \Stripe\Charge::create(array(
            "amount" => $ticket->getPrice() * 100,
            "currency" => "eur",
            "source" => $token,
            "description" => "Commande Louvre!"));

    }

    public function createCustomer(Customer $user, $paymentToken)
    {
        $customer = \Stripe\Customer::create([
            'email' => $user->getAdresseEmail(),
            'source' => $paymentToken,
        ]);
        $user->setStripeCustomerId($customer->id);

        return $customer;
    }
    public function updateCustomerCard(Customer $user, $paymentToken)
    {
        $customer = \Stripe\Customer::retrieve($user->getStripeCustomerId());
        $customer->source = $paymentToken;
        $customer->save();
    }

    public function createInvoiceItem($amount, Customer $user, $description)
    {
        return \Stripe\InvoiceItem::create(array(
            "amount" => $amount,
            "currency" => "usd",
            "customer" => $user->getStripeCustomerId(),
            "description" => $description
        ));
    }
    public function createInvoice(Customer $user, $payImmediately = true)
    {
        $invoice = \Stripe\Invoice::create(array(
            "customer" => $user->getStripeCustomerId()
        ));
        if ($payImmediately) {
            // guarantee it charges *right* now
            $invoice->pay();
        }
        return $invoice;
    }
}