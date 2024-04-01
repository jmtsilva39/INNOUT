<main class="content">
	<?php
	// Função para mostrar o título da página
	renderTitle(
		'Relatório Mensal',
		'Acompanhe seu saldo de horas',
		'icofont-ui-calendar'
	);

	?>
	<div>
		<!-- Início do formulário -->

		<form class="mb-4" action="#" method="post">
			<div class="input-group">
				<?php if ($user->is_admin) : ?>
					<select name="user" id="user" class="form-control mr-2" placeholder="Selecione o usuário...">
						<option value="">Selecione o usuário</option>
						<?php foreach ($users as $userItem) : ?>
							<?php
							$selected = $userItem->id === $selectedUserId ? 'selected' : '';
							$userName = $userItem->name; //htmlentities($userItem->name, ENT_QUOTES, 'UTF-8'); // Aplicando htmlentities()
							?>
							<option value="<?php echo $userItem->id; ?>" <?php echo $selected; ?>><?php echo $userName; ?></option>
						<?php endforeach; ?>
					</select>
				<?php else : ?>
					<!-- Campo de seleção oculto para passar o ID do usuário -->
					<select name="user" id="user" style="display: none;">
						<option value="<?php echo $user->id; ?>"><?php echo $user->name; ?></option>
					</select>
				<?php endif; ?>

				<!-- Campo select para selecionar o período -->
				<select name="period" id="period" class="form-control" placeholder="Selecione o período...">
					<?php foreach ($periods as $key => $month) : ?>
						<?php $selected = $key === $selectedPeriod ? 'selected' : ''; ?>
						<option value="<?php echo $key; ?>" <?php echo $selected; ?>><?php echo $month; ?></option>
					<?php endforeach; ?>
				</select>

				<!-- Botão para GERAR O PDF -->
				<button type="submit" class="btn btn-primary ml-2" formaction="controllerpdf.php">Gerar PDF</button>

				<!-- Botão para GERAR O CSV -->
				<button type="submit" class="btn btn-danger ml-2" formaction="controllercsv.php">Gerar CSV</button>


				<!-- Botão para realizar a busca -->
				<button id="searchButton" name="buscar" class="btn btn-primary ml-2" style="display: none;">
					<i class="icofont-search"></i>
				</button>
			</div>
		</form>



		<!-- Div para os botões -->
		<div class="btn-group" role="group">

			<div class="btn-group" role="group">


			</div>

			<script>
				// Adiciona um listener para o evento onchange no campo select de usuário
				document.getElementById('user').addEventListener('change', function() {
					// Atualiza o valor do campo oculto com o usuário selecionado
					document.getElementById('selectedUser').value = this.value;
				});
			</script>



		</div>

		<!-- Tabela para exibir os resultados -->
		<table class="table table-bordered table-striped table-hover">
			<thead>
				<th>Dia</th>
				<th>Entrada 1</th>
				<th>Saída 1</th>
				<th>Entrada 2</th>
				<th>Saída 2</th>
				<th>Saldo</th>
			</thead>
			<tbody>
				<!-- Conteúdo da tabela preenchido dinamicamente com PHP -->
				<?php
				foreach ($report as $registry) : ?>
					<tr>
						<td><?= formatDateWithLocale($registry->work_date, ' %A, %d/%m/%Y') ?></td>
						<td><?= $registry->time1 ?></td>
						<td><?= $registry->time2 ?></td>
						<td><?= $registry->time3 ?></td>
						<td><?= $registry->time4 ?></td>
						<td><?= $registry->getBalance() ?></td>
					</tr>
				<?php endforeach ?>
				<!-- Linha para exibir o total de horas trabalhadas e saldo mensal -->
				<tr class="bg-primary text-white">
					<td>Horas Trabalhadas</td>
					<td colspan="3"><?= $sumOfWorkedTime ?></td>
					<td>Saldo Mensal</td>
					<td><?= $balance ?></td>
				</tr>
			</tbody>
		</table>
	</div>
</main>

<script>
	// Adiciona um listener para o evento onchange no campo select
	document.getElementById('period').addEventListener('change', function() {
		// Simula o clique no botão de busca
		document.getElementById('searchButton').click();
	});


	// Adiciona um listener para o evento onchange no campo select
	document.getElementById('user').addEventListener('change', function() {
		// Simula o clique no botão de busca
		document.getElementById('searchButton').click();
	});
</script>