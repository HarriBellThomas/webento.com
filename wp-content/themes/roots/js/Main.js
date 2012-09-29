

function changeTab(tabId)
{
if (tabId == "bigProject")
	{
		document.getElementById('BP').className = 'seltab'; 
		document.getElementById('SP').className = '';
		jQuery("#smallProject").hide();
		jQuery("#bigProject").show();
	}	
if (tabId == "smallProject")
	{
		document.getElementById('BP').className = '';
		document.getElementById('SP').className = 'seltab';
	    jQuery("#smallProject").show();
		jQuery("#bigProject").hide();
    }	
	if (tabId == "ourServices")
	{
		document.getElementById('OS').className = 'seltab'; 
		document.getElementById('OP').className = '';
		jQuery("#ourProducts").hide();
		jQuery("#ourServices").show();
	}	
if (tabId == "ourProducts")
	{
		document.getElementById('OP').className = 'seltab'; 
		document.getElementById('OS').className = '';
	    jQuery("#ourProducts").show();
		jQuery("#ourServices").hide();
    }	
}

