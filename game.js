// Create the canvas
var canvas = document.createElement("canvas");
var ctx = canvas.getContext("2d");
canvas.width = 512;
canvas.height = 480;
document.body.appendChild(canvas);

//time to do some defining up here!

//base class
function GamePiece(){
	this.x = 0;
	this.y = 0;
	this.width = 0;
	this.height =0;
	this.ready = true;
	this.image = new Image();
	this.collisionType = 'background';
	this.xMove = function(distance) {
		this.x += distance;
	}
	this.yMove = function(distance) {
		this.y += distance;
	}
	this.addSource = function(source) {
		this.image.src = source;
		this.width = image.width;
		this.height = image.height;
	}
}

function Organism(){
	GamePiece.call(this);
}
Organism.prototype = new GamePiece();
Organism.prototype.constructor = GamePiece();
Organism.prototype.foodValue = 0;
Organism.prototype.hunger = 0;
Organism.prototype.health = 0;
Organism.prototype.defense = 0;
Organism.prototype.offense = 0;
Organism.prototype.speed = 0;
Organism.prototype.sensitivity = 0;
Organism.prototype.species = 0;
Organism.prototype.chromosome = '';
Organism.prototype.collideWith = function(type){};
Organism.prototype.update = function(modifier){};

/*
To do:
-Make the world variable
	-handle all collisions generically
	-Make an array called collidables (or some such shit)
	-every world update, spatially map all of those collidables
	-check each collision box against its 8 corresponding collision boxes (I feel like this should be lower?)
	-compare all of those against eachother, calling the collideWith function as necessary
	-call update on all remaining collidables
-Make the AJAX for the chromosomes and mating (use php for now)
-Make a function which sorts the survivors and then kills off the other assholes.
-Think about the size and scope of the world.  And how animals come to be in it.
-Think about objects like a house and trees which aren't backgrounds!
*/

function Character(){
	GamePiece.call(this);
}

Character.prototype = new GamePiece();
Character.prototype.constructor = GamePiece;
Character.prototype.speed = 0;
Character.prototype.update = function(modifier){};

function Monster(){
	Organism.call(this);
}

Monster.prototype = new Organism();
Monster.prototype.constructor = Organism;
Monster.prototype.sensitivity = 100;
Monster.prototype.update = function(modifier) {
	if (Math.abs(world.player.x - this.x) < this.sensitivity && Math.abs(world.player.y - this.y) < this.sensitivity) {
		var xMod = -(world.player.x - this.x)/Math.abs(world.player.x - this.x);
		var yMod = -(world.player.y - this.y)/Math.abs(world.player.y - this.y);

		this.xMove(this.speed * xMod * modifier);
		this.yMove(this.speed * yMod * modifier);
	}
}

var world = new Object();

var BackGround = new GamePiece();
BackGround.addSource("images/background.png");

var Hero = new Character();
Hero.addSource("images/hero.png");
Hero.speed = 256;

world.player = Hero;

var monsters = [];
world.monsters = monsters;

for (var i = 10; i >= 0; i--) {
	var monster = new Monster();
	monster.addSource("images/monster.png");
	monster.speed = Math.random() * 300;
	// Throw the monster somewhere on the screen randomly
	monster.x = 32 + (Math.random() * (canvas.width - 64));
	monster.y = 32 + (Math.random() * (canvas.height - 64));

	monsters.push(monster);
};

var monstersCaught = 0;

// Handle keyboard controls
var keysDown = {};

addEventListener("keydown", function (e) {
	keysDown[e.keyCode] = true;
}, false);

addEventListener("keyup", function (e) {
	delete keysDown[e.keyCode];
}, false);


Hero.x = canvas.width / 2;
Hero.y = canvas.height / 2;

// Update game objects
var update = function (modifier) {
	if (32 in keysDown) { // Player pressing spacebar
	}
	if (38 in keysDown) { // Player holding up
		Hero.y -= Hero.speed * modifier;
	}
	if (40 in keysDown) { // Player holding down
		Hero.y += Hero.speed * modifier;
	}
	if (37 in keysDown) { // Player holding left
		Hero.x -= Hero.speed * modifier;
	}
	if (39 in keysDown) { // Player holding right
		Hero.x += Hero.speed * modifier;
	}

	if (Hero.x >= (canvas.width - Hero.image.width)) {
		Hero.x = canvas.width - Hero.image.width;
	} else if (Hero.x < 0) {
		Hero.x = 0;
	}

	if (Hero.y >= (canvas.height - Hero.image.height)) {
		Hero.y = canvas.height - Hero.image.height;
	} else if (Hero.y < 0) {
		Hero.y = 0;
	}

	// Are they touching?
	if (monsters.length-1 >=0){
		//If they collide then deal with that, otherwise update monster style
		for (var i = monsters.length - 1; i >= 0; i--) {
			if (
			Hero.x <= (monsters[i].x + monsters[i].image.width)
			&& monsters[i].x <= (Hero.x + Hero.image.width)
			&& Hero.y <= (monsters[i].y + monsters[i].image.height)
			&& monsters[i].y <= (Hero.y + Hero.image.height)
		) {
			monsters.splice(i, 1); //remove one monster from the array at index i
			++monstersCaught;
			continue;
		} else {
			monsters[i].update(modifier);
		}

		if (monsters[i].x >= (canvas.width - monsters[i].image.width)) {
			monsters[i].x = (canvas.width - monsters[i].image.width-1);
		} else if (monsters[i].x < 0) {
			monsters[i].x = 1;
		}

		if (monsters[i].y >= (canvas.height - monsters[i].image.height)) {
			monsters[i].y = (canvas.height - monsters[i].image.height -1);
		} else if (monsters[i].y < 0) {
			monsters[i].y = 1;
		}

		};
	};
};

// Draw everything
var render = function () {
	if (BackGround.ready) {
		ctx.drawImage(BackGround.image, 0, 0);
	}

	if (Hero.ready) {
		ctx.drawImage(Hero.image, Hero.x, Hero.y);
	}
	for (var i = monsters.length - 1; i >= 0; i--) {	
		if (monsters[i].ready) {
			ctx.drawImage(monsters[i].image, monsters[i].x, monsters[i].y);
		}
	};

	// Score
	ctx.fillStyle = "rgb(250, 250, 250)";
	ctx.font = "24px Helvetica";
	ctx.textAlign = "left";
	ctx.textBaseline = "top";
	ctx.fillText("Goblins caught: " + monstersCaught, 32, 32);
};

// The main game loop
var main = function () {
	var now = Date.now();
	var delta = now - then;

	update(delta / 1000);
	render();

	then = now;
};

// Let's play this game!
var then = Date.now();
setInterval(main, 1); // Execute as fast as possible
