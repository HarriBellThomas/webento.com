

function changeTab(tabId)
{
if (tabId == "bigProject")
	{
		jQuery("#smallProject").hide();
		jQuery("#bigProject").show();
	}	
if (tabId == "smallProject")
	{
	    jQuery("#smallProject").show();
		jQuery("#bigProject").hide();
    }	
}

