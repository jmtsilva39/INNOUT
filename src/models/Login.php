<?php

 class Login extends Model{

    //Validando os dados que estamos passando no formulário de login

    public function validate(){
        $errors = [];

        if(!$this->email){
            $errors['email'] = 'E-mail é um campo obrigatório';
        }

        if (!$this->password) {
            $errors['password'] = 'Por favor informe a senha';
        }

        if(count($errors) > 0) {
            throw new ValidationException($errors);
        }
    }

    public function checkLogin(){
        $this->validate();
        $user = User::getOne(['email' => $this->email]);
        //usuário deve ser encontrado no banco de dados
        if($user){
            if($user->end_date){
                throw new AppException('Usuário está desligado da empresa');  
            }

            //verifica se a senha está correta
            if(password_verify($this->password, $user->password)){
                return $user;
            }
        }
        throw new AppException('Usuário/Senha inválidos');
    }
}
