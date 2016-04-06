/*
	Automagically
	15.04.15, db
	functions for updating sliders and search:
	when slider is moved update the value in textbox
	and also update the search (results being displayed)
*/

$(document).ready(function() {

	// when slider is moved call update function
	$('#budgetSlider').on('change', function(){
		updateSliderAndSearch('budget');
	});
	
	$('#durationSlider').on('change', function(){
		updateSliderAndSearch('duration');
	});
	
	$('#amountSlider').on('change', function(){
		updateSliderAndSearch('amount');
	});
	
	$('#varietySlider').on('change', function(){
		updateSliderAndSearch('variety');
	});
	
	// function that updates slider value and the search
	function updateSliderAndSearch(slider){
		
		if(slider == 'budget') printSliderValueBudget('budgetSlider', 'rangeValueBudget');
		
		if(slider == 'duration') printSliderValueDuration('durationSlider', 'rangeValueDuration');
		
		if(slider == 'amount') printSliderValue('amountSlider', 'rangeValueAmount'); 
		
		if(slider == 'variety') printSliderValue('varietySlider', 'rangeValueVariety'); 
		
		search();		
	}
	
	// when search is started, call this function and pass the params via post
	function search(){
		$.post(
			'search.php',
			{
				budget: $('#rangeValueBudget').val(),
				duration: $('#rangeValueDuration').val(),
				amount: $('#rangeValueAmount').val(),
				variety: $('#rangeValueVariety').val()
			},
			function(data){
				$('#result').html(data);
			}
		);
	}
});