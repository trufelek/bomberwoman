//wzór planszy
Board.template = [
	[
		'WWWWWWWWWWWWWWW',
		'W             W',
		'W X X X X X X W',
		'W             W',
		'W X X X X X X W',
		'W             W',
		'W X X X X X X W',
		'W             W',
		'W X X X X X X W',
		'W             W',
		'WWWWWWWWWWWWWWW'
	]
];

//elementy planszy
Board.elements = {
	'floor':{sx:144, sy:64, type:'empty', sub_type:'board'},
	'W': {sx:160, sy:64, type:'solid', sub_type:'board'},
	'X': {sx:176, sy:64, type:'solid', sub_type:'board'},
	'crate': {sx:96, sy:48, type:'soft', sub_type:'board', destructible: 'yes'}
};

//funkcja odwzorowania planszy
function Board(){
	// zamieniam łańcuchy znaków na tablice obiektów
	this.parse(Board.template[Game.rand(0,Board.template.length-1)]);
	//dodawanie skrzynek
	for(var i=0; i<15; i++){
		this.addCrate();
	}
	// wielkość elementów
	this.fW = 16;
	this.fH = 16;
}

//funkcja dodająca skrzynki
Board.prototype.addCrate = function(){
	var position = this.getEmptySpace();
	if(position){
		this.b[position.y][position.x] = Board.elements.crate;
	}
}

//funkcja sprawdzająca czy dany element tablicy jest pusty
Board.prototype.getEmptySpace = function(){
	return this.emptySpaces.length>0 ? this.emptySpaces.shift() : null;
}

//funkcja rysująca plansze
Board.prototype.draw = function() {
	// rysujemy po kolei każdy rząd czyli łańcuch znaków
	for(var i=0; i<this.b.length; i++){
		// druga pętla sprawdza każdy znak aktualnego łańcucha znaków
		for(var j=0; j<this.b[i].length; j++){
			// rysowanie obrazka
			Game.ctx.drawImage(
				Game.spr,
				this.b[i][j].sx,
				this.b[i][j].sy,
				this.fW,
				this.fH,
				j*this.fW*Game.scale,//  j to pozycja elementu w poziomie, mnożę ją przez szerokość elementu i skalę gry
				i*this.fH*Game.scale,// i to indeks całwgo rzędu
				this.fW*Game.scale,//szerokość wklejanego elementu
				this.fH*Game.scale// wysokość wklejanego elementu
			);
			if(this.b[i][j].sub_type != 'board'){
				this.b[i][j].draw();
			}
		}
	}
};

//parsowanie planszy
Board.prototype.parse = function(array){
	this.b = [];
	this.emptySpaces = [];
	for(var i=0; i<array.length; i++){
		// dodaję nową tablicę, która reprezentuje nowy rząd
		this.b.push([]);
		// druga pętla sprawdza każdy znak aktualnego łańcucha znaków
		for(var j=0; j<array[i].length; j++){
			// zamiast łańcucha znaków przechowuję obiekty z informacją o danym polu
			this.b[i].push(Board.elements[array[i].charAt(j)==' ' ? 'floor' : array[i].charAt(j)]);
			//wstawiam nowy obiekt na wolne miejsce
			if(this.b[i][j].type=='empty' && !(i==1 && j==1) && !(i==2 && j==1) && !(i==1 && j==2)){
				this.emptySpaces.push({x:j, y:i});
			}
		}
	}

	Game.shuffle(this.emptySpaces);
}