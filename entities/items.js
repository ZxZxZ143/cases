class Item {
    constructor(name, price) {
        this.price = price;
        this.name = name;
    }

    getItemPath() {
       return "img/items/" + this._name.toLowerCase() + ".jpg";
    }

    get name() {
        return this._name;
    }

    set name(value) {
        this._name = value;
    }

    get price() {
        return this._price;
    }

    set price(value) {
        if (value <= 0) {
            console.log("цена не может быть меньше или равна нулю");
        } else {
            this._price = value;
        }
    }
}