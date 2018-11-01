function menu()
{
	var x=document.getElementById("menu");
	if (x.style.display.match("block"))
	{	
		x.style.display="none";	
	}
	else
	{
		x.style.display="block";
	}
}