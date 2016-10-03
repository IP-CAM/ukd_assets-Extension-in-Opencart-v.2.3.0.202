<!--
********************************************************************************
   UKD - UKITA DEVELOPMENT
--------------------------------------------------------------------------------
   Extension : Ukd Pagseguro
   Ext. Code : ukd_pagseguro_209004f2 
   Filename  : ukd_pagseguro\upload\system\library\ukd_pagseguro_app\templates\orderData.inc.php
   Author    : Fred Ukita
   Date      : Tuesday 13th of September 2016 07:52:06 PM 
********************************************************************************
--><div class="groupData" id="cartData">
	
	<div id="carWrapper">
		
		<h4>Items</h4>
		
		<table id="cartTable">
			<thead>
				<tr>
					<th>Id</th>
					<th>Descri&ccedil;&atilde;o</th>
					<th>Valor Unit&aacute;rio</th>
					<th>Quantidade</th>
					<th>Valor</th>
				</tr>
			</thead>
			<tbody></tbody>
		</table>
		
		<input type="button" id="addItem" value="Adicionar Item" />
		
	</div>
	
	<h3 id="cartTotal"> Valor Total: R$ <span id="totalValue">0.00</span> </h3>
	
	<div id="cartItemHidden" class="cartItem">
		
		<div class="cartItemFields">
			
			<div class="field">
				<label>Item Id:</label>
				<input type="text" class="itemId" name="itemId" value="0001" />
			</div>
			
			<div class="field">
				<label>Item Description:</label>
				<input type="text" class="itemDescription" name="itemDescription" value="Notebook Prata" />
			</div>
			
			<div class="field">
				<label>Item Amount:</label>
				<input type="text" class="itemAmount" name="itemAmount" value="2,00" />
			</div>
			
			<div class="field">
				<label>Item Quantity:</label>
				<input type="text" class="itemQuantity" name="itemQuantity" value="1" />
			</div>
		
			<button class="button addToCart">Adicionar</button>
		</div>
		
	</div>
	
</div>