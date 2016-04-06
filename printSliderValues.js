/*
	Automagically
	14.04.15, db
	functions to print values for each slider
*/

/* prints selected budget value into a textbox

	parameters:		integers, id of slider/textbox
	return value:	none
	exceptions:		error-alert, if value is invalid
*/
function printSliderValueBudget(sliderId, textbox){
	var textboxValue = document.getElementById(textbox);
	var sliderIdValue = document.getElementById(sliderId);
	
	if(sliderIdValue.value == 1){
		textboxValue.value = '100';
	} else if(sliderIdValue.value == 2){
		textboxValue.value = '200';
	} else if(sliderIdValue.value == 3){
		textboxValue.value = '300';
	} else if(sliderIdValue.value == 4){
		textboxValue.value = '500';
	} else if(sliderIdValue.value == 5){
		textboxValue.value = '1000';
	} else if(sliderIdValue.value == 6){
		textboxValue.value = '2000';
	} else if(sliderIdValue.value == 7){
		textboxValue.value = '5000';
	} else if(sliderIdValue.value == 8){
		textboxValue.value = '10000';
	} else if(sliderIdValue.value == 9){
		textboxValue.value = '20000';
	} else if(sliderIdValue.value == 10){
		textboxValue.value = '50000';
	} else if(sliderIdValue.value == 11){
		textboxValue.value = '100000';
	} else {
		alert('Error: Invalid value!'); // error message
	}
}

/* prints selected duration value into a textbox

	parameters: 	integers, id of slider/textbox
	return value: 	none
	exceptions:		error-alert, if value is invalid
*/
function printSliderValueDuration(sliderId, textbox){
	var textboxValue = document.getElementById(textbox);
	var sliderIdValue = document.getElementById(sliderId);
	
	if(sliderIdValue.value == 1){
		textboxValue.value = '1 day';
	} else if(sliderIdValue.value == 2){
		textboxValue.value = '1 week';
	} else if(sliderIdValue.value == 3){
		textboxValue.value = '2 weeks';
	} else if(sliderIdValue.value == 4){
		textboxValue.value = '3 weeks';
	} else if(sliderIdValue.value == 5){
		textboxValue.value = '1 year';
	} else if(sliderIdValue.value == 6){
		textboxValue.value = '2 years';
	} else if(sliderIdValue.value == 7){
		textboxValue.value = '3 years';
	} else if(sliderIdValue.value == 8){
		textboxValue.value = '4 years';
	} else if(sliderIdValue.value == 9){
		textboxValue.value = '5 years';
	} else {
		alert('Error: Invalid value!'); // error message
	}	
}

/* prints selected amount/variety value into a textbox

	parameters: 	integers, id of slider/textbox
	return value: 	none
	exceptions:		error-alert, if value is invalid
*/
function printSliderValue(sliderId, textbox){
	var textboxValue = document.getElementById(textbox);
	var sliderIdValue = document.getElementById(sliderId);
	
	if(sliderIdValue.value == 1){
		textboxValue.value = 'low';
	} else if(sliderIdValue.value == 2){
		textboxValue.value = 'med. low';
	} else if(sliderIdValue.value == 3){
		textboxValue.value = 'medium';
	} else if(sliderIdValue.value == 4){
		textboxValue.value = 'med. high';
	} else if(sliderIdValue.value == 5){
		textboxValue.value = 'high';
	} else {
		alert('Error: Invalid value!'); // error message
	}
}

// when document is ready, invoke all functions
window.onload = function(){
	printSliderValueBudget('budgetSlider', 'rangeValueBudget');
	printSliderValueDuration('durationSlider', 'rangeValueDuration');
	printSliderValue('amountSlider', 'rangeValueAmount');
	printSliderValue('varietySlider', 'rangeValueVariety');
}
