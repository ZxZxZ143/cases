class Case {
    constructor(name, price, items) {
        this.name = name;
        this.price = price;
        this.items = items;
    }

    getCasePath() {
        return "img/cases/" + this._name.toLowerCase() + '.jpg'
    }

    get price() {
        return this._price;
    }

    set price(value) {
        if (value <= 0) {
            throw new Error('Цена не можеть быть ниже или равна 0')
        }
        this._price = value;
    }

    get name() {
        return this._name;
    }

    set name(value) {
        this._name = value;
    }
}

