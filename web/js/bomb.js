//licznik bomb
Bomb.count = 0;
//maksymalna ilość
Bomb.max_count = 3;

//wybuchy bomby
Bomb.elements = {
	'bomb':      {sx:96, sy:64, f:[0,0,1,1,2,2]},
	'center_bum':{sx:240, sy:24, f:[0,0,1,1,2,2,3,3,2,2,1,1,0,0]},
	'up_bum':    {sx:240, sy:56, f:[0,0,1,1,2,2,3,3,2,2,1,1,0,0]},
	'down_bum':    {sx:240, sy:56, f:[0,0,1,1,2,2,3,3,2,2,1,1,0,0]},
	'left_bum':  {sx:240, sy:89, f:[0,0,1,1,2,2,3,3,2,2,1,1,0,0]},
	'right_bum':  {sx:240, sy:89, f:[0,0,1,1,2,2,3,3,2,2,1,1,0,0]},
	'up_bum_end':    {sx:240, sy:40, f:[0,0,1,1,2,2,3,3,2,2,1,1,0,0]},
	'down_bum_end':    {sx:240, sy:40, f:[0,0,1,1,2,2,3,3,2,2,1,1,0,0], flip:true},
	'left_bum_end':  {sx:240, sy:72, f:[0,0,1,1,2,2,3,3,2,2,1,1,0,0]},
	'right_bum_end':  {sx:240, sy:72, f:[0,0,1,1,2,2,3,3,2,2,1,1,0,0], flip:true}
};

//bomba
function Bomb(column,row, bum_type){
	if((Bomb.max_count>Bomb.count && Game.board.b[row][column].sub_type!='bomb' && !bum_type) || (bum_type && Game.board.b[row][column].sub_type=='board' && !Game.board.b[row][column].bum_type)){
		if(!bum_type){
			Bomb.count++;
		}
		this.sx = Board.elements.floor.sx;
		this.sy = Board.elements.floor.sy;
		this.current_f = 0;
		this.type = bum_type ? 'empty' : 'solid';
		this.sub_type = 'bomb';
		this.data = Bomb.elements[bum_type || this.sub_type];
		this.bum_type = bum_type
		this.column = column;
		this.row = row;
		//zasięg bomby
		this.range = 2;
		//timer bomby
		this.timer = bum_type ? this.data.f.length : 30;
		Game.board.b[this.row][this.column] = this;
	}
}
Bomb.prototype.draw = function() {
	if(this.timer>0){
		//rysuje bombe
		this.target_x = this.column*Game.board.fW*Game.scale;
		this.target_y = this.row*Game.board.fH*Game.scale;
		//odbicie lustrzane wybuchów
		if(this.data.flip){
			Game.ctx.save();
			if(this.bum_type=='down_bum_end'){
				Game.ctx.scale(1,-1);
				this.target_y = this.target_y*-1-(Game.board.fH*Game.scale);
			}else{
				Game.ctx.scale(-1,1);
				this.target_x = this.target_x*-1-(Game.board.fW*Game.scale);
			}
		}
		Game.ctx.drawImage(
			Game.spr,
			this.data.sx+this.data.f[this.current_f]*Game.board.fW,
			this.data.sy,
			Game.board.fW,
			Game.board.fH,
			this.target_x,
			this.target_y,
			Game.board.fW*Game.scale,
			Game.board.fH*Game.scale
		);
		if(this.data.flip){
			Game.ctx.restore();
		}
		this.current_f = this.current_f+1>=this.data.f.length ? 0 : this.current_f+1;
		this.timer--;
	}else if(this.type=='solid'){
		if(!this.bum_type){
			Bomb.count--
		}
		this.type = 'empty';
		this.bum_type = 'center_bum';
		this.data = Bomb.elements['center_bum'];
		this.current_f = 0;
		this.timer = this.data.f.length;
		this.bums = [];
		//cztery kierunki wybuchów
		for (var i=0; i<4; i++) {
			this.axis = i%2 ? 'tmp_column' : 'tmp_row';
			this.grow = i%3 ? true : false;
			this.tmp_row = this.row;
			this.tmp_column = this.column;
			if(this.axis=='tmp_column' && this.grow){
				this.tmp_bum_type = 'right_bum';
			}else if(this.axis=='tmp_column' && !this.grow){
				this.tmp_bum_type = 'left_bum';
			}else if(this.axis=='tmp_row' && !this.grow){
				this.tmp_bum_type = 'up_bum';
			}else if(this.axis=='tmp_row' && this.grow){
				this.tmp_bum_type = 'down_bum';
			}
			for (var j=0; j<this.range; j++) {
				this[ this.axis ] = this[ this.axis ]+(this.grow ? 1 : -1);
				//jeśli trafi na podłogę lub skrzynkę
				if(Game.board.b[ this.tmp_row ][ this.tmp_column ].type!='solid'){
					this.tmp_crate = Game.board.b[ this.tmp_row ][ this.tmp_column ].type=='soft';
					// sprawdzam czy to była skrzynka
					if(Game.board.b[ this.tmp_row ][ this.tmp_column ].ko_obj){
						new window[ Game.board.b[ this.tmp_row ][ this.tmp_column ].ko_obj ](this.tmp_column, this.tmp_row);
					}else{
						new Bomb(this.tmp_column, this.tmp_row, this.tmp_bum_type+(j==this.range-1 ? '_end' : ''));
					}
					if(this.tmp_crate){
						break;
					}
				//aktywacja drugiej bomby
				}else if(Game.board.b[ this.tmp_row ][ this.tmp_column ].sub_type=='bomb' && !Game.board.b[ this.tmp_row ][ this.tmp_column ].bum_type){
					Game.board.b[ this.tmp_row ][ this.tmp_column ].timer = 0;
					break;
				}else{
					break;
				}
			}
		}

	}else{
		Game.board.b[this.row][this.column] = Board.elements.floor;
	}
};