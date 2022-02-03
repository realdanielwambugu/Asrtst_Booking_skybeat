class Route extends Api
{
	constructor(config)
	{
		if(!location.hash)
		{
			location.hash = config.index[0];
			if(config.index.length > 1)
			{
				location.hash = config.index[0] + '//index';
				config.index.splice(1, config.index.length - 1);
			}
		}
		super(config);
		this.partialsCache = {};
		this.route();
		$(window).on('hashchange', this.route.bind(this));

	}
	route()
	{
		var url = location.hash.substr(1);
		var fragmentId = url;
		var data = false;
		if(url.includes("//"))
		{
			fragmentId = url.split('//')[0];
			data = url.substring(url.indexOf('//') + 2)
			data = data.replace(/%20/g, ' ');
		}
		var self = this;
		this.getTemplate(fragmentId, function(content)
		{
			$("#template").html(content);
			if(data)
			{
				self.set(data);
			}
		});
		this.setActiveLink(fragmentId);
	}
	getTemplate(fragmentId, callback)
	{
		var self = this;
		if(self.partialsCache[fragmentId])
		{
			callback(self.partialsCache[fragmentId]);
		}
		else
		{
			$.get(fragmentId + ".html", function(content)
			{
				self.partialsCache[fragmentId] = content;
				callback(content);
			});
		}
	}
	setActiveLink(fragmentId)
	{
		$("#navbar a").each(function(i, linkElement)
		{
			var link = $(linkElement),
				pageName = link.attr("href").substr(1);
			if(pageName === fragmentId)
			{
				link.attr("class", "active");
			}
			else
			{
				link.removeAttr("class");
			}
		});
	}
}
