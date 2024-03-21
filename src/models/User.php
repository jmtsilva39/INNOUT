<?php
// Classe User representa um modelo para interações com a tabela 'users' no banco de dados
class User extends Model
{
    // Nome da tabela 'users'
    protected static $tableName = 'users';
    // Colunas associadas aos atributos do modelo User
    protected static $columns = [
        'id',
        'name',
        'password',
        'email',
        'start_date',
        'end_date',
        'is_admin'
    ];

    // Retorna a contagem de usuários ativos
    public static function getActiveUsersCount()
    {
        return static::getCount(['raw' => 'end_date IS NULL']);
    }

    // Valida e insere um novo usuário no banco de dados
    public function insert()
    {
        $this->validate();
        $this->is_admin = $this->is_admin ? 1 : 0;
        if (!$this->end_date) $this->end_date = null;
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        return parent::insert();
    }

    // Valida e atualiza um usuário no banco de dados
    public function update()
    {
        $this->validate();
        $this->is_admin = $this->is_admin ? 1 : 0;
        if (!$this->end_date) $this->end_date = null;
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        return parent::update();
    }

    // Método privado para validação dos dados do usuário
  
    private function validate()
    {
        $errors = [];

        if (!$this->name) {
            $errors['name'] = 'Nome é um campo abrigatório.';
        }

        if (!$this->email) {
            $errors['email'] = 'Email é um campo abrigatório.';
        } elseif (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email inválido.';
        }

        /*if (!$this->start_date) {
            $errors['start_date'] = 'Data de Admissão é um campo abrigatório.';
        } elseif (!DateTime::createFromFormat('Y-m-d', $this->start_date)) {
            $errors['start_date'] = 'Data de Admissão deve seguir o padrão dd/mm/aaaa.';
        }*/

        if ($this->end_date && !DateTime::createFromFormat('Y-m-d', $this->end_date)) {
            $errors['end_date'] = 'Data de Desligamento deve seguir o padrão dd/mm/aaaa.';
        }

        if (!$this->password) {
            $errors['password'] = 'Senha é um campo abrigatório.';
        }

        if (!$this->confirm_password) {
            $errors['confirm_password'] = 'Confirmação de Senha é um campo abrigatório.';
        }

        if (
            $this->password && $this->confirm_password
            && $this->password !== $this->confirm_password
        ) {
            $errors['password'] = 'As senhas não são iguais.';
            $errors['confirm_password'] = 'As senhas não são iguais.';
        }

        // Lança uma exceção com os erros de validação, se houver algum
        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }
    }
}
