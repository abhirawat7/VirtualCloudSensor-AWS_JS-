// MEAN Stack RESTful API Tutorial - Contact List App

var express = require('express');
var app = express();
var mongojs = require('mongojs');
var db = mongojs('clouduser:user@ds151927.mlab.com:51927/cloudsensor',['sensorlist']);
var bodyParser = require('body-parser');
var moment = require('moment-timezone');
var tc = require("timezonecomplete");   
var NodeGeocoder = require('node-geocoder');
var session = require('express-session');

var sess;

app.use(session({
    secret: 'secret',
    saveUninitialized: false,
    resave: false,
    HttpOnly: false, 
    
}));

var options = {
  provider: 'google',
 
  // Optional depending on the providers 
  httpAdapter: 'https', // Default 
  apiKey: 'AIzaSyDevpxCh5pqYdIJB36HInOMYIEbwm7_uH4', // for Mapquest, OpenCage, Google Premier 
  formatter: null         // 'gpx', 'string', ... 
};

var geocoder = NodeGeocoder(options);

app.use(express.static(__dirname + '/public'));
app.use(bodyParser.json());

//list of sensors
app.get('/sensorlist/:name', function (req, res) {
  console.log('I received a GET request');
	var name = req.params.name;
  db.sensorlist.find({"username" : name}, function (err, docs) {
    console.log(docs);
    res.json(docs);
  });
});

//insert sensor data
app.post('/sensorlist', function (req, res) {
  console.log(req.body);
  //creation date -> today date
  req.body.creationDate = moment().tz("America/Los_Angeles").format("MM-DD-YYYY");
  //creation time -> current time
  req.body.creationTime = moment().tz("America/Los_Angeles").format("HH:mm");
  req.body.uptime = moment().tz("America/Los_Angeles").format("MM-DD-YYYY  HH:mm:ss");
  req.body.duration = 0;
  
	//get latitude and longitude from location
	geocoder.geocode(req.body.location, function(err, resp) {
		console.log(resp[0].latitude + " and " + resp[0].longitude);
		req.body.latitude=resp[0].latitude;
		req.body.longitude=resp[0].longitude;
		
		db.sensorlist.insert(req.body, function(err, doc) {
			console.log("data entry done");
			res.json(doc);
		});
	});
});

//delete sensor
app.delete('/sensorlist/:id', function (req, res) {
  var id = req.params.id;
  console.log(id);
  db.sensorlist.remove({_id: mongojs.ObjectId(id)}, function (err, doc) {
    res.json(doc);
	
  });
});

//update state of sensor (active/deactive) 
app.put('/sensorlist/:id/:state', function (req, res) {
  var id = req.params.id;
  //console.log(req.params.id);
  //console.log(req.params.state);
  db.sensorlist.findAndModify({
    query: {_id: mongojs.ObjectId(id)},
    update: {$set: {"state": req.params.state.toString()}},
    new: true}, function (err, doc) {
      res.json	(doc);
    }
  );
});

//update uptime/downtime of sensor (active/deactive) 
app.put('/sensorlisttime/:id/:state', function (req, res) {
  var id = req.params.id;
  //console.log(req.params.id);
  //console.log(req.params.state);
  
  
  if(req.params.state == "Active"){ //active -> update uptime
		db.sensorlist.findAndModify({
		query: {_id: mongojs.ObjectId(id)},
		update: {$set: {"uptime": moment().tz("America/Los_Angeles").format("MM-DD-YYYY HH:mm:ss")}},
		new: true}, function (err, doc) {
		  res.json	(doc);
		}
	);
  }
  else { //deactive -> update downtime
		db.sensorlist.findAndModify({
		query: {_id: mongojs.ObjectId(id)},
		update: {$set: {"downtime": moment().tz("America/Los_Angeles").format("MM-DD-YYYY HH:mm:ss")}},
		new: true}, function (err, doc) {
		  res.json	(doc);
		}
	);
  }
  
});

//update billing into database when user deactivate the sensor
app.put('/sensorbilling/:id', function (req, res){
	var id = req.params.id;
	//***** Billing
	//find row based on _id
	db.sensorlist.findOne({_id: mongojs.ObjectId(id)}, function (err, doc) {
		
		console.log('billing');
		console.log(doc.uptime);
		console.log(doc.downtime);
		var cost=0.0;
		var startTime = doc.downtime;
		var endTime = doc.uptime;
		//difference between uptime and downtione in HH:mm format
		console.log(startTime+" and "+endTime);
		
		//test
		
		//find difference between uptime and downtime -> working time of sensor
		var ms = moment(startTime,"MM-DD-YYYY HH:mm").diff(moment(endTime,"MM-DD-YYYY HH:mm"));
		var d = moment.duration(ms);
		var timeDifference = Math.floor(d.asHours()) + moment.utc(ms).format(":mm:ss");
		
		console.log(timeDifference);
		
		//convert into minutes
		var timeParts = timeDifference.split(':');    
		var minutes=Number(timeParts[0])*60+Number(timeParts[1]);
		console.log(minutes);
		
		var duration = Number(doc.duration) + minutes;
		//calculate cost based on minutes (difference between uptime and downtime)
		if(doc.type == "Bus Sensor" || doc.type == "Bus Stop Sensor"){
			cost = Number(doc.bill) + 0.20*minutes;
		}
		else{
			cost = Number(doc.bill) + 0.30*minutes;  
		}
		console.log(cost);
		
		//update bill into database
		db.sensorlist.findAndModify({
			query: {_id: mongojs.ObjectId(id)},
			update: {$set: {"bill": Math.round(cost * 100) / 100, "duration":duration}},
			new: true}, function (err, doc) {
			  res.json	(doc);
		});
		
		//res.json(doc);
	
	});
	
});

//user sign up
//insert user details
app.post('/userlist', function (req, res) {
  console.log(req.body);
  db.userlist.insert(req.body, function(err, doc) {
    res.json(doc);
  });
});

//check valid username and password
app.get('/userlist/:name/:password', function (req, res) {
	
  console.log('I received a GET request');

  db.userlist.find({},{"username":1,"password":1,"type":1,_id:0}).toArray(function (err, docs) {
	console.log(docs);
	//console.log(docs[0].username);
	flag = "unsuccessful";
	
	for (i=0; i<docs.length; i++)
	{
		if(docs[i].username.toString() == req.params.name.toString() & docs[i].password.toString() == req.params.password.toString())
		{
			console.log('successful');
			flag = "successful"
			break;
		}
	}
	
	if(flag == "successful")
	{
		/*res.setHeader('Set-Cookie', cookie.serialize('username', String(req.params.name), {
			httpOnly: true,
		}));*/
		sess = req.session;
		sess.username=req.params.name;
		res.send("successful");
	}
	else
	{
		res.send("unsuccessful");
	}
	
  });
});


//session check
app.get('/sessioncheck', function (req, res) {
	
  console.log('I received a session check request');
  sess = req.session;
	
  if(sess.username)
  {
	  res.send(sess.username);
  }
  else
  {
	  res.send("not exist");
  }
	
});

//session destroy
app.get('/sessiondestroy', function (req, res) {
	
  console.log('I received a session destroy request');
  sess = req.session;
  sess.destroy(function(err) {
        if(err){
             console.log('Error destroying session');
			 res.send("not done");
        }else{
            console.log('Session destroy successfully');
			res.send("done");
        }
    });
	
});

//to find type of user
app.get('/usertype/:name', function (req, res) {
	
  console.log('I received a GET request');

  db.userlist.findOne({"username":req.params.name},(function (err, docs) {
	console.log(docs);
	res.send(docs.type);

	}));
});

//list of sensors for admin
app.get('/sensorlist_admin', function (req, res) {
	
  console.log('I received a GET request');

  db.sensorlist.find({},(function (err, docs) {
	console.log(docs);
	res.send(docs);

	}));
});

//user profile info for admin and normal user
app.get('/userprofile/:name', function (req, res) {
	
  console.log('I received a GET request');

  db.userlist.findOne({"username":req.params.name},(function (err, docs) {
	console.log(docs);
	res.send(docs);

	}));
});

app.get('/getsensordata/:group/:type', function (req, res) {
	
  console.log('I received a GET request');

  db.sensordata.findOne({"group": req.params.group, "type": req.params.type},(function (err, docs) {
	console.log(docs);
	res.send(docs);

	}));
});


app.listen(3000);
console.log("Server running on port 3000");