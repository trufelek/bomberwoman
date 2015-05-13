//skrzynka
function Crate(column, row){
	this.sx = Board.elements.floor.sx;
	this.sy = Board.elements.floor.sy;
	this.anim_sx = 96;
	this.anim_sy = 48;
	this.current_f = 0;
	this.f = [0,0,1,1,2,2,3,3,4,4,5,5];
	this.type = 'empty';
	this.sub_type = 'crate';
	this.row = row;
	this.column = column;
	Game.board.b[this.row][this.column] = this;
}

//rysowanie skrzynki
Crate.prototype.draw = function() {
	Game.ctx.drawImage(
		Game.spr,
		this.anim_sx+this.f[this.current_f]*Game.board.fW,
		this.anim_sy,
		Game.board.fW,
		Game.board.fH,
		this.column*Game.board.fW*Game.scale,
		this.row*Game.board.fH*Game.scale,
		Game.board.fW*Game.scale,
		Game.board.fH*Game.scale

	);
	this.current_f ++;
	if(this.current_f>=this.f.length){
		Game.board.b[this.row][this.column] = Board.elements.floor;
	}
};