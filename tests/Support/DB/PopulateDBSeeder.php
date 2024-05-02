<?php

namespace Tests\Support\DB;

use App\Models\User;
use App\Models\Concessionaria;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

// php artisan db:seed --class="\\Tests\\Support\\DB\\PopulateDBSeeder" --env=testing
class PopulateDBSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $concessionariaKnownData = [
            ['name' => 'Concessionaria 1', 'cnpj' => '36320010000151', 'symbol' => 'AABBC',],
            ['name' => 'Concessionaria 2', 'cnpj' => '36320010000151', 'symbol' => 'AABBD',],
            ['name' => 'Concessionaria 3', 'cnpj' => '36320010000151', 'symbol' => 'AABBE',],
            ['name' => 'Silva e Rico', 'cnpj' => '85634157000121', 'symbol' => 'AABBF',],
            ['name' => 'Pereira e Filhos', 'cnpj' => '25304073000106', 'symbol' => 'AABBG',],
            ['name' => 'Uchoa-Toledo', 'cnpj' => '71156473000120', 'symbol' => 'AABBH',],
            ['name' => 'Silva e Filho', 'cnpj' => '73132632000182', 'symbol' => 'AABBI',],
            ['name' => 'Silva Comercial Ltda', 'cnpj' => '84702120000120', 'symbol' => 'AABBJ',],
            ['name' => 'Concessionaria 9', 'cnpj' => '12342120000121', 'symbol' => 'XYZ',],
            ['name' => 'Concessionaria 10', 'cnpj' => '12342120000122', 'symbol' => 'XYZ',],
        ];

        $user0 = User::factory()
                ->has(
                    Concessionaria::factory()
                                ->count(10)
                                ->state(new Sequence(...$concessionariaKnownData))
                )
                ->create(['email' => 'user_0@example.org']);

        // Criar 9 usuários cada usuário contendo 10 concessionárias
        $users = User::factory()
                ->count(9)
                ->sequence(fn (Sequence $sequence) => ['email' => 'user_' . ($sequence->index + 1) . '@example.org'])
                ->has(Concessionaria::factory()->count(10))
                ->create();

        // Usuário desabilitado
        User::factory()->disabled()->create();

        // Usuário com email não verificado
        User::factory()->unverified()->create();
    }
}
