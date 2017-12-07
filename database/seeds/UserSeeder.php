<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $adm = new Shoppvel\User();
        $adm->name = 'Adiministrador';
        $adm->email = 'admin@admin.com';
        $adm->cpf = '00000000000';
        $adm->role = 'admin';
        $adm->password = bcrypt('123456');
        $adm->save();
        
        $caix = new Shoppvel\User();
        $caix->name = 'Caixa';
        $caix->email = 'admin_caixa@hotmail.com';
        $caix->cpf = '00000000000';
        $caix->role = 'admin/caixa';
        $caix->password = bcrypt('123456');
        $caix->save();

        $coz = new Shoppvel\User();
        $coz->name = 'Cozinha';
        $coz->email = 'cozinha@cozinha.com';
        $coz->cpf = '00000000000';
        $coz->role = 'cozinha';
        $coz->password = bcrypt('123456');
        $coz->save();

        $recp = new Shoppvel\User();
        $recp->name = 'Recepcao';
        $recp->email = 'recepcao@gmail.com';
        $recp->cpf = '00000000000';
        $recp->role = 'recepcao';
        $recp->password = bcrypt('123456');
        $recp->save();

    }
    /*public function run(){
        $u = new lanchonete\User();
        $u->name = 'Cozinha';
        $u->email = 'cozinha@cozinha.com';
        $u->cpf = '00000000000';
        $u->role = 'cozinha';
        $u->password = bcrypt('123456');
        $u->save();
    }*/

}