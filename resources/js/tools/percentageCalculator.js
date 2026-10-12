export default function percentageCalculator() {
    return {
        tab: 'simple', // 'simple' | 'change' | 'discount'

        // Mode 1: What is X% of Y?
        simplePct: 15,
        simpleVal: 200,

        get simpleResult() {
            const p = parseFloat(this.simplePct);
            const v = parseFloat(this.simpleVal);
            if (isNaN(p) || isNaN(v)) return 0;
            return parseFloat(((p / 100) * v).toFixed(4));
        },

        // Mode 2: Percentage Change from X to Y
        changeFrom: 50,
        changeTo: 75,

        get changeResult() {
            const f = parseFloat(this.changeFrom);
            const t = parseFloat(this.changeTo);
            if (isNaN(f) || isNaN(t)) return { change: 0, diff: 0, direction: 'none', error: null };
            if (f === 0) {
                return { change: 0, diff: t - f, direction: 'none', error: 'Division by zero: initial value is 0.' };
            }
            const diff = t - f;
            const pct = (diff / Math.abs(f)) * 100;
            const dir = pct > 0 ? 'increase' : pct < 0 ? 'decrease' : 'none';
            return {
                change: parseFloat(Math.abs(pct).toFixed(2)),
                diff: parseFloat(diff.toFixed(2)),
                direction: dir,
                error: null
            };
        },

        // Mode 3: Discount Calculator
        discPrice: 1000,
        discPct: 20,

        get discountSavings() {
            const p = parseFloat(this.discPrice);
            const d = parseFloat(this.discPct);
            if (isNaN(p) || isNaN(d)) return 0;
            return parseFloat(((d / 100) * p).toFixed(2));
        },

        get discountFinal() {
            const p = parseFloat(this.discPrice);
            if (isNaN(p)) return 0;
            return parseFloat(Math.max(0, p - this.discountSavings).toFixed(2));
        },

        resetSimple() {
            this.simplePct = '';
            this.simpleVal = '';
        },

        resetChange() {
            this.changeFrom = '';
            this.changeTo = '';
        },

        resetDiscount() {
            this.discPrice = '';
            this.discPct = '';
        }
    };
}
