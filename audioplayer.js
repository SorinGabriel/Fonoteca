	var play=0;
	var pause=1;
	var speed=1;
	
	function startplay()
	{
		var x=document.getElementById("AP");
		x.play();
		var y=document.getElementById("APplay");
		var z=document.getElementById("APpause");
		z.style.display="inline-block";
		y.style.display="none";
		play=1;
		pause=0;
		setInterval(function(){write()},1000);
	}
	
	function startpause()
	{
		play=0;
		pause=1;
		var x=document.getElementById("AP");
		var y=document.getElementById("APplay");
		var z=document.getElementById("APpause");
		y.style.display="inline-block";
		z.style.display="none";
		x.pause();
	}
	
	function changespeed()
	{
		var x=document.getElementById("AP");
		var y=document.getElementById("APrate");
		x.playbackRate=y.value;
		speed=y.value;
	}
	
	function write()
	{
		var x=document.getElementById("APtime");
		var y=document.getElementById("AP");
		var t=Math.round(y.currentTime);
		var m=Math.floor(t/60);
		var s=t%60;
		if (s<10) 
		{
			s="0"+s;
		}
		x.innerHTML="0"+m+":"+s;
	}
	
	function toend()
	{
		var y=document.getElementById("AP");
		y.currentTime=280;
	}
	
	function changetrack($src)
	{
		var aud=document.getElementById("AP");
		aud.src = $src;
        aud.load();
        if (play==1 && pause==0)
		{
			aud.playbackRate=speed;
			aud.play();	
		}
	}
	
	function changetrk(song)
	{
		var next=document.getElementsByClassName('songs');
		var j;
		for (var i=0;i<next.length;i++)
		{
			if (next[i]==song)
			{
				j=i;
			}
			else if (next[i]!=song && next[i].classList.contains("active"))
			{
				next[i].classList.remove("active");
			}
		}
		changetrack(next[j].name);
		var h=document.getElementById("APname");
		h.innerHTML=next[j].value;
		if (j<next.length)
		{
			next[j].classList.add("active");
		}
	}
	
	var repeat=false;
	
	function repeatplaylist()
	{
		var x=document.getElementById("repeatbut");
		if (repeat==false)
		{
			repeat=true;
			x.style.backgroundColor="grey";
		}
		else 
		{
			repeat=false;
			x.style.backgroundColor="transparent";
		}
	}
	
	function nexttrack() 
	{
		var next=document.getElementsByClassName('songs');
		var j;
		for (var i=0;i<next.length;i++)
		{
			if (next[i].classList.contains("active"))
			{
				next[i].classList.remove("active");
				j=i+1;
			}
		}
		if (j==next.length && repeat==true)
		{
			j=0;
		}
		changetrack(next[j].name);
		var h=document.getElementById("APname");
		h.innerHTML=next[j].value;
		if (j<next.length)
		{
			next[j].classList.add("active");
		}
	};