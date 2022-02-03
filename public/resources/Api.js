class Api
{
	constructor(config)
	{
		this.preventResubmission();

		this.config = config;
	}


	set(data)
	{
		this.url = this.config.default[0];

		if(data.includes("/"))
    {
			this.data = data.split('/');

	  }
	  else
	  {
			this.data = this.config[data];
			if(data.includes("+"))
			{
				data = data.split('+');

				this.config[data[0]][2] = data[1];

				this.data = this.config[data[0]];

		}
	}

	this.parsedata();

	return  false;
}


parsedata()
{
	const data = this.data
	const Form = document.getElementById(data[2]);

	if(Form)
	{
		var formData = new FormData(Form);
	}
	else
	{
		var formData = new FormData();

	  if (this.isset(data[2]))
		{
				if(data[2])
				{
					const params = data[2].split(',');

						for (var param in params)
						{
								if(params[param].includes("="))
								{
									 const key = params[param].split('=');

									 formData.append(key[0].trim(), key[1].trim());
								}
						}
					}
					else
					{
						console.error('parameters must contain a name like (name = value)');
					}
	  }
	}

	formData.append(0, data[0].trim());
	formData.append(1, data[1].trim());

	var loader = this.config.default[1];
	var receiver = this.isset(data[3]) ? document.getElementById(data[3]) : null;

  var plus_data = false;

	if (this.isObject(data[4]) || this.isset(data[5]))
	{
		  const dataObject = this.isObject(data[4]) ? data[4] : data[5];

	  	plus_data = 'increment' in dataObject ? dataObject['increment'] : plus_data;

	  	loader = 'loader' in dataObject ? dataObject['loader'] : loader;
	}

	var loader_area = receiver;

	if (!this.isObject(data[4]) && this.isset(data[4]))
	{
		  loader_area = document.getElementById(data[4]);
	}

	if (loader_area)
	{
			var prevData = loader_area.innerHTML;

			loader_area.innerHTML = loader;
	}

	if(!loader)
	{
		loader = 'loading...'
	}

	this.request(formData, this.url, function(response)
	{
		  if (loader_area != receiver && loader_area)
			{
		  	 loader_area.innerHTML = prevData;
		  }

			if (response.match(new RegExp("\\b" + "redirect:" + "\\b")))
			{
        return location.hash = response.split(':')[1];
			}

	    if (receiver)
			{
					if(plus_data)
					{
						 return receiver.innerHTML += response;
					}

			   	return receiver.innerHTML = response;
	    }

			console.error('No html tag set to receive response data');

	});
}


async request(formData, url, callback)
{
	await fetch(url,
	{
		method: 'POST',
		body: formData

	}).then(function(response)
	{
		return response.text();

	}).then(function(text)
	{
		callback(text);

	}).catch(function(error)
	{
		console.error(error);
	})
}

infinity(data)
{
	// window.addEventListener("scroll", function(){
	//    console.log('hrloo');
	//
	// });
}

isObject(data)
{
	 return typeof data === 'object' && data != null;
}

isset(key)
{
	 return typeof key != 'undefined';
}

preventResubmission()
{
	if ( window.history.replaceState )
	{
			window.history.replaceState( null, null, window.location.href );
	}
}

}
