var e = [];
for(var i = 0; i<a[0].length; i++){
	e.push([]);
	for(var j = 0; j<a[0].length; j++){
		if(i==j){
			e[i][j]=math.fraction(1,1);
		}
		else{
			e[i][j]=math.fraction(0);
		}
	}
}
e = $M(e);
a[0] = $M(a[0]);
var alg = a[0].dup();
a[0] = a[0].augment(e).elements;