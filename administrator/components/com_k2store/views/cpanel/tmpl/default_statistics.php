<?php

?>
<div class="k2store_statistics">
<h3><?php echo JText::_('K2STORE_ORDER_STATISTICS');?></h3>
	<table class="adminlist table table-bordered table-striped">
	<thead>
		<th><?php echo JText::_(''); ?></th>
		<th><?php echo JText::_('K2STORE_TOTAL'); ?></th>
		<th><?php echo JText::_('K2STORE_AMOUNT'); ?></th>
	</thead>
	<tbody>
		<tr>
			<td><?php echo JText::_('K2STORE_TOTAL_ORDERS'); ?></td>
			<td>
			<?php
				echo $this->order_model->clearState()->nozero(0)->getOrdersTotal();
			?>
			</td>
			<td>
			<?php
				echo K2StorePrices::number($this->order_model->clearState()->nozero(0)->moneysum(1)->getOrdersTotal());
			?>
			</td>
		</tr>

		<tr>
			<td><?php echo JText::_('K2STORE_TOTAL_CONFIRMED_ORDERS'); ?></td>
			<td>
			<?php
				echo $this->order_model->clearState()->paystate(1)->nozero(1)->getOrdersTotal();
			?>
			</td>
			<td>
			<?php
				echo K2StorePrices::number($this->order_model->clearState()->paystate(1)->nozero(1)->moneysum(1)->getOrdersTotal());
			?>
			</td>
		</tr>

		<tr>
			<td><?php echo JText::_('K2STORE_TOTAL_CONFIRMED_ORDERS_LAST_YEAR'); ?></td>
			<td>
			<?php
				echo $this->order_model->clearState()
									->since((gmdate('Y')-1).'-01-01 00:00:00')
									->until((gmdate('Y')-1).'-12-31 23:59:59')
									->paystate(1)
									->nozero(1)
									->getOrdersTotal();
			?>
			</td>
			<td>
			<?php
			echo K2StorePrices::number(
				$this->order_model->clearState()
									->since((gmdate('Y')-1).'-01-01 00:00:00')
									->until((gmdate('Y')-1).'-12-31 23:59:59')
									->paystate(1)
									->nozero(1)
									->moneysum(1)
									->getOrdersTotal()
			);
			?>
			</td>
		</tr>

		<tr>
			<td><?php echo JText::_('K2STORE_TOTAL_CONFIRMED_ORDERS_THIS_YEAR'); ?></td>
			<td>
			<?php
				echo $this->order_model->clearState()
									->since(gmdate('Y').'-01-01')
									->until(gmdate('Y').'-12-31 23:59:59')
									->paystate(1)
									->nozero(1)
									->getOrdersTotal();
			?>
			</td>
			<td>
			<?php
			echo K2StorePrices::number(
				$this->order_model->clearState()
									->since(gmdate('Y').'-01-01')
									->until(gmdate('Y').'-12-31 23:59:59')
									->paystate(1)
									->nozero(1)
									->moneysum(1)
									->getOrdersTotal()
			);
			?>
			</td>
		</tr>

		<tr>
			<td><?php echo JText::_('K2STORE_TOTAL_CONFIRMED_ORDERS_LAST_MONTH'); ?></td>
			<td>
			<?php
							$y = gmdate('Y');
							$m = gmdate('m');
							if($m == 1) {
								$m = 12; $y -= 1;
							} else {
								$m -= 1;
							}
							switch($m) {
								case 1: case 3: case 5: case 7: case 8: case 10: case 12:
									$lmday = 31; break;
								case 4: case 6: case 9: case 11:
									$lmday = 30; break;
								case 2:
									if( !($y % 4) && ($y % 400) ) {
										$lmday = 29;
									} else {
										$lmday = 28;
									}
							}
							if($y < 2011) $y = 2011;
							if($m < 1) $m = 1;
							if($lmday < 1) $lmday = 1;
			?>
			<?php
				echo $this->order_model->clearState()
									->since($y.'-'.$m.'-01')
									->until($y.'-'.$m.'-'.$lmday.' 23:59:59')
									->paystate(1)
									->nozero(1)
									->getOrdersTotal();
			?>
			</td>
			<td>
			<?php
			echo K2StorePrices::number(
				$this->order_model->clearState()
									->since($y.'-'.$m.'-01')
									->until($y.'-'.$m.'-'.$lmday.' 23:59:59')
									->paystate(1)
									->nozero(1)
									->moneysum(1)
									->getOrdersTotal()
			);
			?>
			</td>
		</tr>

		<tr>
			<td><?php echo JText::_('K2STORE_TOTAL_CONFIRMED_ORDERS_THIS_MONTH'); ?></td>
			<td>
			<?php
							switch(gmdate('m')) {
								case 1: case 3: case 5: case 7: case 8: case 10: case 12:
									$lmday = 31; break;
								case 4: case 6: case 9: case 11:
									$lmday = 30; break;
								case 2:
									$y = gmdate('Y');
									if( !($y % 4) && ($y % 400) ) {
										$lmday = 29;
									} else {
										$lmday = 28;
									}
							}
							if($lmday < 1) $lmday = 28;
						?>
			<?php
				echo $this->order_model->clearState()
									->since(gmdate('Y').'-'.gmdate('m').'-01')
									->until(gmdate('Y').'-'.gmdate('m').'-'.$lmday.' 23:59:59')
									->paystate(1)
									->nozero(1)
									->getOrdersTotal();
			?>
			</td>
			<td>
			<?php
			echo K2StorePrices::number(
				$this->order_model->clearState()
									->since(gmdate('Y').'-'.gmdate('m').'-01')
									->until(gmdate('Y').'-'.gmdate('m').'-'.$lmday.' 23:59:59')
									->paystate(1)
									->nozero(1)
									->moneysum(1)
									->getOrdersTotal()
			);
			?>
			</td>
		</tr>

		<tr>
			<td><?php echo JText::_('K2STORE_TOTAL_CONFIRMED_ORDERS_LAST7DAYS'); ?></td>
			<td>
			<?php
				echo $this->order_model->clearState()
									->since( gmdate('Y-m-d', time()-7*24*3600) )
									->until( gmdate('Y-m-d') )
									->paystate(1)
									->nozero(1)
									->getOrdersTotal();
			?>
			</td>
			<td>
			<?php
			echo K2StorePrices::number(
				$this->order_model->clearState()
									->since( gmdate('Y-m-d', time()-7*24*3600) )
									->until( gmdate('Y-m-d') )
									->paystate(1)
									->nozero(1)
									->moneysum(1)
									->getOrdersTotal()
			);
			?>
			</td>
		</tr>


		<tr>
			<td><?php echo JText::_('K2STORE_TOTAL_CONFIRMED_ORDERS_YESTERDAY'); ?></td>
			<td>
			<?php
			$date = new DateTime();
			$date->setDate(gmdate('Y'), gmdate('m'), gmdate('d'));
			$date->modify("-1 day");
			$yesterday = $date->format("Y-m-d");
			$date->modify("+1 day")
			?>
			<?php
				echo $this->order_model->clearState()
									->since( $yesterday )
									->until( $date->format("Y-m-d") )
									->paystate(1)
									->nozero(1)
									->getOrdersTotal();
			?>
			</td>
			<td>
			<?php
			echo K2StorePrices::number(
				$this->order_model->clearState()
									->since( $yesterday )
									->until( $date->format("Y-m-d") )
									->paystate(1)
									->nozero(1)
									->moneysum(1)
									->getOrdersTotal()
			);
			?>
			</td>
		</tr>


		<tr>
			<td><strong><?php echo JText::_('K2STORE_TOTAL_CONFIRMED_ORDERS_TODAY'); ?></strong></td>
			<td><strong>
			<?php
			$expiry = clone $date;
			$expiry->modify('+1 day');
			?>
			<?php
				echo $this->order_model->clearState()
									->since( $date->format("Y-m-d") )
									->until( $expiry->format("Y-m-d") )
									->paystate(1)
									->nozero(1)
									->getOrdersTotal();
			?>
			</strong>
			</td>
			<td>
			<strong>
			<?php
			echo K2StorePrices::number(
				$this->order_model->clearState()
									->since( $date->format("Y-m-d") )
									->until( $expiry->format("Y-m-d") )
									->paystate(1)
									->nozero(1)
									->moneysum(1)
									->getOrdersTotal()
			);
			?>
			</strong>
			</td>
		</tr>

		<tr>
			<td><strong><?php echo JText::_('K2STORE_TOTAL_CONFIRMED_ORDERS_AVERAGE'); ?></strong></td>

			<?php
						switch(gmdate('m')) {
							case 1: case 3: case 5: case 7: case 8: case 10: case 12:
								$lmday = 31; break;
							case 4: case 6: case 9: case 11:
								$lmday = 30; break;
							case 2:
								$y = gmdate('Y');
								if( !($y % 4) && ($y % 400) ) {
									$lmday = 29;
								} else {
									$lmday = 28;
								}
						}
						if($lmday < 1) $lmday = 28;
						if($y < 2011) $y = 2011;
						$daysin = gmdate('d');
						$numsubs = $this->order_model->clearState()
							->since(gmdate('Y').'-'.gmdate('m').'-01')
							->until(gmdate('Y').'-'.gmdate('m').'-'.$lmday.' 23:59:59')
							->nozero(1)
							->paystate(1)
							->getOrdersTotal();
						$summoney = $this->order_model->clearState()
							->since(gmdate('Y').'-'.gmdate('m').'-01')
							->until(gmdate('Y').'-'.gmdate('m').'-'.$lmday.' 23:59:59')
							->moneysum(1)
							->paystate(1)
							->getOrdersTotal();
					?>

			<td>
				<strong><?php echo sprintf('%01.1f', $numsubs/$daysin)?><strong>
			</td>
			<td>
			<strong>
			<?php
			echo K2StorePrices::number(
					sprintf('%01.2f', $summoney/$daysin)
			);
			?>
			</strong>
			</td>
		</tr>



	</tbody>



	</table>


</div>