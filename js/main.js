jQuery(document).ready(function($)
{
	// site preloader -- also uncomment the div in the header and the css style for #preloader
	$(window).load(function()
	{
		$('#preloader').fadeOut('slow',function(){$(this).remove();});
	});


  //To show tip on click
  var alreadyShowsTip = false;
	$( "#tipbtn" ).click(function()
	{


		if(alreadyShowsTip == false)
		{
			alreadyShowsTip = true;
			$( "#tip" ).remove();
			$( ".tip" ).append( "<p>It's in alphabetic order!</p>" );
		}


  });

	//To show solution on click
	var alreadyShowsSolution = false;
	$( "#solutionbtn" ).click(function()
	{
		if(alreadyShowsSolution == false)
		{
			alreadyShowsSolution = true;
			$( "#solution" ).remove();
			$( ".solution" ).append( "<p>Help Heimerdinger kidnapped us! We are currently being kept in the basement of Piltover Customs he needed two developers because he wanted to study every champion in order to become Masterdinger; please send Corki, Ziggs or Gnar to try to reason with him and if needed bring Rumble, Tahm Kench or Viktor to kill him, please send help!</p>" );
		}


  });

	//About page show tip and solution
});
