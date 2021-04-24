// CONSTANTS START
const ROOT_IMG_CASE_FOLDER = 'img/cases/';
const ROOT_IMG_ITEMS_FOLDER = 'img/items/';

// CONSTANTS END

class Box {
    constructor(caseName, casePrice, items) {
        this.name = caseName;
        this.price = casePrice;
        this.items = items;
    }

    getCasePath() {
        return ROOT_IMG_CASE_FOLDER + this._name.toLowerCase() + '.jpg'
    }

    getItemsPath() {
        return ROOT_IMG_ITEMS_FOLDER + this._items.toLowerCase() + '.jpg'
    }

    get items() {
        return this._items;
    }

    set items(value) {
        this._items = value;
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

const boxes = [];

boxes.push(new Box('PRISMA2', 200, "1.png, 2.png, 3.png"));
boxes.push(new Box('prisma2000', 2_000_000, "1.png, 2.png, 3.png"));
boxes.push(new Box('prisma3000', 30_000_000));

let object = JSON.stringify(boxes);

const boxes2 = JSON.parse(object);

const cases = [];

addCase(boxes2[1]._name, boxes2[1]._price, cases, "1.png, 2.png, 3.png");
addCase(boxes2[0]._name, boxes2[0]._price, cases, "1.png, 2.png, 3.png");

console.log(cases);

cases.forEach(cases => {
    console.log(`Case: ${cases._name} price: ${cases._price} Img path: ${cases.getCasePath()} 
    Items path: ${cases.getItemsPath()} Items: ${cases._items}`);
});


function addCase(caseName, casePrice, massiveName, items = []) {
    massiveName.push(new Box(caseName, casePrice, items));
}

