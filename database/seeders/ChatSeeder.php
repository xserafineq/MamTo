<?php

namespace Database\Seeders;

use App\Models\Auction;
use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Seeder;

class ChatSeeder extends Seeder
{
    public function run(): void
    {
        $buyer = User::where('email', 'jan.kowalski@example.com')->first();
        $buyer2 = User::where('email', 'anna.nowak@example.com')->first();
        $auctions = Auction::with('user')->take(6)->get();

        if (!$buyer || !$buyer2 || $auctions->count() < 3) {
            $this->command->warn('Uruchom najpierw UserSeeder i AuctionSeeder.');

            return;
        }

        $samples = [
            [
                'buyer' => $buyer,
                'auction' => $auctions[0],
                'messages' => [
                    ['sender' => 'buyer', 'text' => 'Dzień dobry, czy aukcja jest nadal aktualna?', 'sentAt' => now()->subDays(2)->setTime(21, 37)],
                    ['sender' => 'seller', 'text' => 'Tak, przedmiot jest dostępny.', 'sentAt' => now()->subDays(2)->setTime(22, 10)],
                    ['sender' => 'buyer', 'text' => 'Czy możliwy jest odbiór osobisty?', 'sentAt' => now()->subDay()->setTime(18, 5)],
                ],
            ],
            [
                'buyer' => $buyer,
                'auction' => $auctions[1],
                'messages' => [
                    ['sender' => 'buyer', 'text' => 'Interesuje mnie negocjacja ceny.', 'sentAt' => now()->subDays(3)->setTime(14, 20)],
                    ['sender' => 'seller', 'text' => 'Proszę o propozycję.', 'sentAt' => now()->subDays(3)->setTime(15, 0)],
                ],
            ],
            [
                'buyer' => $buyer2,
                'auction' => $auctions[2],
                'messages' => [
                    ['sender' => 'buyer', 'text' => 'Czy mogę obejrzeć przedmiot w weekend?', 'sentAt' => now()->subHours(5)],
                ],
            ],
        ];

        foreach ($samples as $sample) {
            $auction = $sample['auction'];
            $buyerUser = $sample['buyer'];

            if ((int) $auction->userId === (int) $buyerUser->id) {
                continue;
            }

            $chat = Chat::create([
                'auctionId' => $auction->id,
                'sellerId' => $auction->userId,
                'buyerId' => $buyerUser->id,
            ]);

            foreach ($sample['messages'] as $messageData) {
                Message::create([
                    'chatId' => $chat->id,
                    'text' => $messageData['text'],
                    'sentAt' => $messageData['sentAt'],
                    'senderId' => $messageData['sender'] === 'buyer'
                        ? $buyerUser->id
                        : $auction->userId,
                ]);
            }
        }
    }
}
