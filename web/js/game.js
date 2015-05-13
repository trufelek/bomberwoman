//inicjowanie gry po wczytaniu całej strony
window.onload = function(){
	Game.spr = new Image();
	Game.spr.onload = Game.init();
	Game.spr.src = 'grafika/bomber.png';
}


Game = {
	//podstawowe wartości
	fps:16,
	W:0,
	H:0,
	scale:4,
	lastTime:0,
	//tworzymy obiekt przechowujący elementy do narysowania w każdej klatce
	toDraw:{},

	//funkcja losująca
	rand:function(min,max){
		return Math.floor(Math.random()*(max-min+1))+min;
	},

	//funkcja tasująca
	shuffle:function(array){
		var counter = array.length;
		var tmp;
		var index; 
		while(counter>0){
			counter--;
			index = Math.floor(Math.random() * counter);
			tmp = array[counter];
			array[counter] = array[index];
			array[index] = tmp;
		}
		return array;
	},

	//funkcja inicjująca gre
	init:function(){
		//tworzenie canvas i kontekst
		Game.canvas = document.createElement('canvas');
		Game.ctx = Game.canvas.getContext('2d');
		//punkty 
		Game.playerScore = 0;
		//stworzenie planszy
		Game.board = new Board();
		//dodanie gracza
		Game.hero = new Hero();
		//dodanie potworów
		var tmp_empty;
		var number_of_enemies = 5;
		for (var i=0; i<number_of_enemies; i++) {
			tmp_empty = Game.board.getEmptySpace();
			new Enemy(tmp_empty.x*Game.board.fW, tmp_empty.y*Game.board.fH);
		}
		//nasłuchiwanie klawiatury
		window.addEventListener('keydown', Game.onKey, false);
		window.addEventListener('keyup', Game.onKey, false);
		Game.layout();
		//nasłuchiwanie zmiany wielkości okna
		window.addEventListener('resize', Game.layout, false);
		document.body.appendChild(Game.canvas);
		Game.animation();
	},

	//funkcja layoutu odpalana przy zmianie wielkości okna
	layout:function(event){
		Game.W = window.innerWidth;
		Game.H = window.innerHeight;
		Game.canvas.width = Game.W;
		Game.canvas.height = Game.H;
		
		//skala canvas
		Game.scale = Math.max(1, Math.min(
			Math.floor(Game.W/(Game.board.fW*Game.board.b[0].length)),
			Math.floor(Game.H/(Game.board.fH*Game.board.b.length))
		));

		// skalowanie szerokości i wysokości
		Game.canvas.width = Math.round(Game.scale*Game.board.fW*Game.board.b[0].length);
		Game.canvas.height = Math.round(Game.scale*Game.board.fH*Game.board.b.length);

		//wyśrodkowanie canvas
		Game.canvas.style[('transform')]= 'translate('+Math.round((Game.W-Game.canvas.width)/2)+'px,'+Math.round((Game.H-Game.canvas.height)/2)+'px)'

		Game.ctx.imageSmoothingEnabled = false;
		Game.ctx.mozImageSmoothingEnabled = false;
		Game.ctx.oImageSmoothingEnabled = false;
		Game.ctx.webkitImageSmoothingEnabled = false;
	},

	//funkcja obsługi klawiatury
	onKey:function(event){
		if((event.keyCode>=37 && event.keyCode<=40) || event.keyCode==32){
			if(event.type=='keydown' && !Game['key_'+event.keyCode]){
				Game['key_'+event.keyCode] = true;
				if(event.keyCode>=37 && event.keyCode<=40){
					for(var i=37; i<=40; i++){
						if(i!=event.keyCode){
							Game['key_'+i] = false;
						}
					}
					Game.hero.updateState();
				}else{
					new Bomb(Game.hero.column, Game.hero.row);
				}
			}else if(event.type=='keyup'){
				Game['key_'+event.keyCode] = false;
				if(event.keyCode!=32){
					Game.hero.updateState();
				}
			}
		}
	},

	stop:function(){
		window.removeEventListener('keydown', Game.onKey);
		window.removeEventListener('keyup', Game.onKey);
	},

	//funkcja animacji gry
	animation:function(time){
		requestAnimationFrame(Game.animation);
		//ograniczenie ilości klatek do zdefiniowanych fps
		if(time-Game.lastTime>=1000/Game.fps){
			Game.lastTime = time;
			//wyczyszczenie canvas
			Game.ctx.clearRect(0,0,Game.W, Game.H);
			//aktualizacja wyniku
			document.getElementById('score').innerHTML = Game.playerScore;
			//rysowanie planszy
			Game.board.draw();
			//rysowanie obiektów
			for(var object in Game.toDraw){
				Game.toDraw[object].draw();

			}

		}
	}
}