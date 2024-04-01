<main class="content">
    <?php
    renderTitle(
        'Seja Bem vindo!',
        'Seja bem vindo(a) ao seu Sistema de Ponto eletrônico!',
        'icofont-check-alt'
    );
    include(TEMPLATE_PATH . "/messages.php");
    ?>
    <div class="card">
        <div class="card-header">
            <h3><?= $today ?></h3>
            <p class="mb-0"></p>
        </div>

        <div class="card-body">
            <div class="d-flex m-5 justify-content-around">
                <p>É com grande satisfação que damos as boas-vindas ao nosso sistema de ponto eletrônico! Estamos felizes em recebê-lo nesta plataforma que não apenas simplificará o controle de sua jornada de trabalho, mas também trará maior eficiência e praticidade para o seu dia a dia. A partir de agora, você terá acesso a uma ferramenta moderna e intuitiva, projetada para facilitar o registro preciso e o gerenciamento de suas horas de trabalho. Esperamos que você aproveite ao máximo todas as funcionalidades oferecidas e que esta experiência seja enriquecedora e produtiva. Seja bem-vindo e conte conosco para qualquer suporte ou assistência que precisar!</p>

            </div>

            <div class="d-flex m-5 justify-content-around">
                <!-- Botão para gerar PDF -->
 
            </div>
        </div>


        <div class="card-footer d-flex justify-content-center">

        </div>
    </div>

    <form class="mt-5" action="#" method="post">
        <div class="input-group no-border">
            <h3></h3>
        </div>
    </form>

</main>