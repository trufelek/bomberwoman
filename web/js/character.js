//zliczanie postaci
Character.count = 0;

//postać
function Character(inheritance){
	Character.count++;
	this.id = 'char_'+Character.count;
	if(!inheritance){
		Game.toDraw[this.id] = this;
	}
	//prędkość postaci
	this.speed = 2;	
	//przesunięcie postaci
	this.modX = -3;
	this.modY = -9;
	//stany postaci
	this.states = {};
	//obecna klatka
	this.current_f = 0;
	//opóźnienie klatek
	this.change_f_delay = 0;
	this.f_max_delay = 2;
}

//funkcja rysowania postaci
Character.prototype.draw = function(){
	//sprawdzam czy postać jest w ruchu
		if(this.state.slice(-2)=='go'){
		if(this.state=='down_go'){
			this.y+=this.speed
		}else if(this.state=='up_go'){
			this.y-=this.speed
		}else if(this.state=='left_go'){
			this.x-=this.speed
		}else if(this.state=='right_go'){
			this.x+=this.speed
		}
		this.coordinates();
	}

	//sprawdzam czy postać stoi na wybuchu
		if(Game.board.b[this.row][this.column].sub_type=='bomb' && Game.board.b[this.row][this.column].bum_type){
		this.setKO();
	}

	Game.ctx.drawImage(
		Game.spr,
		this.states[this.state].sx+this.states[this.state].f[this.current_f]*this.fW,
		this.states[this.state].sy,
		this.fW,
		this.fH,
		(this.x+this.modX)*Game.scale,
		(this.y+this.modY)*Game.scale,
		this.fW*Game.scale,
		this.fH*Game.scale
		);

	if(this.change_f_delay<this.f_max_delay){
		this.change_f_delay++;
	}else{
		this.change_f_delay = 0;
			if(this.state=='ko' && this.current_f== this.states[this.state].f.length-1){
			this.afterKO();
		}else{
		this.current_f = this.current_f+1>=this.states[this.state].f.length ? 0 : this.current_f+1;
		}
	}
};

// ustawiam stan na ko
Character.prototype.setKO = function(){
	this.state = 'ko';
} 
// usuwam z obiektu toDraw
Character.prototype.afterKO = function(){
	delete Game.toDraw[this.id];
}

//funkcja sprawdzająca na jakie pola wchodzi postać
Character.prototype.coordinates = function() {
	this.column = Math.round(this.x/Game.board.fW);
	this.row = Math.round(this.y/Game.board.fH);
	//ustalam na jakie pole wchodzi postać
	if(this.state.slice(-3)=='_go'){
		if(this.state=='left_go' || this.state=='right_go'){
			this.next_column = this.state=='left_go' ? Math.floor(this.x/Game.board.fW) : Math.ceil(this.x/Game.board.fW);
			this.next_row = this.row;
		}else{
			this.next_row = this.state=='up_go' ? Math.floor(this.y/Game.board.fW) : Math.ceil(this.y/Game.board.fW);
			this.next_column = this.column;
		}
		//sprawdzam czy może wejść na to pole
		if(!(this.next_row==this.row && this.next_column==this.column) && Game.board.b[this.next_row][this.next_column].type!='empty'){
			//jeśli nie, usuwam stan "go" i ustawiam pierwszą klatkę animacji
			this.state = this.state.slice(0,-3);
			this.current_f = 0;
			//ustawiam postać z powrotem na kwadracie z którego wychodziła
			if(this.next_row!=this.row){
				this.y = this.row*Game.board.fH;
			}else{
				this.x = this.column*Game.board.fW;
			}
		//jeśli tak, wyśrodkowuje postać względem rzędu i kolumny
		}else{
			if(this.next_row!=this.row){
				this.x = this.next_column*Game.board.fW;
			}else if(this.next_column!=this.column){
				this.y = this.next_row*Game.board.fH;
			}
		}
	//jeśli postać nie porusza się, następne pole jest takie samo jak obecne
	}else{
		this.next_column = this.column;
		this.next_row = this.row;
	}
};

//bohater
function Hero(){
	Character.call(this);
	this.fW = 24;
	this.fH = 24;
	//wszystkie stany bohatera
	this.state = 'down';
	this.states = {
		'down':{sx:0, sy:0, f:[0]},
		'down_go':{sx:0, sy:0, f:[1,0,2,0]},
		'up':{sx:144, sy:0, f:[0]},
		'up_go':{sx:144, sy:0, f:[1,0,2,0]},
		'left':{sx:72, sy:0, f:[0]},
		'left_go':{sx:72, sy:0, f:[1,0,2,0]},
		'right':{sx:216, sy:0, f:[2]}, 
		'right_go':{sx:216, sy:0, f:[1,2,0,2]}, 
		'ko':{sx:0, sy:24, f:[0,1,0,1,0,2,3,4,5,6,7,8,9]},
		'win':{sx:192, sy:48, f:[0,1,0,1,0,1]}
	}

	this.x = Game.board.fW;
	this.y = Game.board.fH;
	this.coordinates();
}
// dziedziczenie hero z klasy character
Hero.prototype = new Character(true);
Hero.prototype.construktor = Hero;
Hero.prototype.parent = Character.prototype;

//zmiana stanów poruszającego się bohatera
Hero.prototype.updateState = function(){
	if(Game.key_37){
		this.tmpstate = 'left_go';
	}else if(Game.key_38){
		this.tmpstate = 'up_go';
	}else if(Game.key_39){
		this.tmpstate = 'right_go';
	}else if(Game.key_40){
		this.tmpstate = 'down_go';
	}else if(this.state.slice(-2)=='go'){
		this.tmpstate = this.state.slice(0, this.state.indexOf('_go') );
	}
	if(this.tmpstate!=this.state){
		this.current_f = 0;
		this.state = this.tmpstate;
	}
}

// rozszerzam metodę setKO
Hero.prototype.setKO = function(){
	this.parent.setKO.call(this);
	// gracz nie może już sterować bohaterem
	Game.stop();
}

// rozszerzam metodę setKO
Hero.prototype.afterKO = function(){
	delete Game.toDraw[this.id];
	// tutaj może pojawić się game over
	if(!Game.is_over){
		Game.is_over = true;
		var score = Game.playerScore;
    	window.location.href = "http://localhost/bomberwoman/web/statistics?score=" + score;
	}
}

// funkcja sprawdza czy bohater zderzył się z misiem
Hero.prototype.enemyCollision = function(){
	for(var e in Enemy.all){
		e = Enemy.all[e];
		// porównuję koordynaty gracza i misia
		if((this.row==e.row && e.x+Game.board.fW>this.x && e.x<this.x+Game.board.fW) || (this.column==e.column && e.y+Game.board.fH>this.y && e.y<this.y+Game.board.fH)){
			//jeśli zderzył się
			return true;
		}
	} 
	// jeśli nie zderzył się
	return false;
}
// rozszerzam metodę draw
Hero.prototype.draw = function(){
	this.parent.draw.call(this);
	// ustawiam KO jeśli gracz został zabity
	if(this.state!='ko' && this.enemyCollision()){
		this.setKO();
	}
}

//przechowywanie wszystkich przeciwników
Enemy.all = {};

//przeciwnicy
function Enemy(x,y){
	Character.call(this);
	Enemy.all[this.id] = this;
	this.fW = 16;
	this.fH = 16;
	this.modX = 0;
	this.modY = 0;
	//wszystkie stany przeciwników
	this.state = 'down';
	this.states = {
		'down':{sx:0, sy:48, f:[0]}, 
		'down_go':{sx:0, sy:48, f:[0,1,2,3,4,5]}, 
		'up':{sx:0, sy:48, f:[0]}, 
		'up_go':{sx:0, sy:48, f:[0,1,2,3,4,5]}, 
		'left':{sx:0, sy:48, f:[0]}, 
		'left_go':{sx:0, sy:48, f:[0,1,2,3,4,5]}, 
		'right':{sx:0, sy:48, f:[0]}, 
		'right_go':{sx:0, sy:48, f:[0,1,2,3,4,5]}, 
		'ko':{sx:0, sy:64, f:[0,0,1,2,3,4,5]}
	}
	this.x = x;
	this.y = y;
	this.coordinates();
	this.direction();
}

//dziedziczenie enemy z klasy character
Enemy.prototype = new Character(true);
Enemy.prototype.construktor = Enemy;
Enemy.prototype.parent = Character.prototype;

//funkcja ustawiająca kierunek ruchu potworów
Enemy.prototype.direction = function(){
	this.canGo = this.canGo || [];
	this.canGo.length = 0;
	//sprawdzam pola wokół potwora
	for(var i=this.column-1; i<=this.column+1; i++){
		for(var j=this.row-1; j<=this.row+1; j++){
			if(!(i==this.column && j==this.row)){
				if(i==this.column || j==this.row){
					if(Game.board.b[j][i].type=='empty'){
						this.canGo.push({x:i, y:j});
					}
				}
			}
		}
	}
	//jeśli pola są puste ustawiam losowy kierunek
	if(this.canGo.length>0){
		this.tmp_pos = this.canGo[Game.rand(0,this.canGo.length-1)];
		if(this.column<this.tmp_pos.x){
			this.state = 'right_go';
		}else if(this.column>this.tmp_pos.x){
			this.state = 'left_go';
		}else if(this.row<this.tmp_pos.y){
			this.state = 'down_go';
		}else if(this.row>this.tmp_pos.y){
			this.state = 'up_go';
		}
	}else if(this.state.slice(-2)=='go'){// jeśli nie ma gdzie iść, trzeba stać (jeśli jeszcze nie stoi)
		this.state = this.state.slice(0, this.state.indexOf('_go') );
	}
}

Enemy.prototype.coordinates = function(){
	this.previous_state = this.state;
	this.parent.coordinates.call(this);
	//jeśi stan się zmienił, wybieram nowy kierunek
	if(this.previous_state!=this.state && this.state.slice(-2)!='go' && this.previous_state.slice(-2)=='go'){
		this.direction();
	}

}

// rozszerzam metodę afterKO
Enemy.prototype.afterKO = function(){
	this.parent.afterKO.call(this);
	// usuwam tego wroga z obiektu Enemy.all
	delete Enemy.all[this.id];
	Game.playerScore ++;
	// sprawdzam czy zostali jeszcze jacyś wrogowie
	var some_enemy = false
	for(var e in Enemy.all){
		some_enemy ++;
		break;
	}
	// jeśli nie, gracz wygrywa
	if(!some_enemy){
		Game.hero.state = 'win';
		var score = Game.playerScore;
    	window.location.href = "http://localhost/bomberwoman/web/statistics?score=" + score;

	}
}
