class BusinessCalculator {
    constructor(options) {
        this.options = { root: null };
        this.revPercentage = 10;
        this.multiplier = 1.0;
        if (options.root == null) {
            console.error("[BusinessCalculator] No root element has been defined!");
            return;
        }
        this.options = options;
    }
    setRootClasses(root) {
        root.classList.add("container");
        root.classList.add("mt-5");
        root.classList.add("business-calculator");
    }
    setCurrency() {
        this.currency = (this.options.currency !== undefined ? this.options.currency : "$");
        this.currencyOrient = (this.options.currencyOrientation !== undefined ? this.options.currencyOrientation : BC_Currency_Orientation.BEFORE_TEXT);
        if (this.currencyOrient === BC_Currency_Orientation.BEFORE_TEXT) {
            this.insertCSS(".bc__output__revenue .bc__output__revenue__total::before { content: '" + this.currency + "'; font-size: 30px; font-weight: 700; position: relative; }");
            return true;
        }
        else if (this.currencyOrient === BC_Currency_Orientation.AFTER_TEXT) {
            this.insertCSS(".bc__output__revenue .bc__output__revenue__total::after { content: '" + this.currency + "'; font-size: 30px; font-weight: 700; position: relative; }");
            return true;
        }
        else {
            console.error("[BusinessCalculator] Invalid currency orientation provided!");
            return false;
        }
    }
    init() {
        let root = this.options.root;
        if (root == null) {
            return;
        }
        this.setRootClasses(root);
        this.createGlobalCSS();
        this.insertColors();
        if (!this.setCurrency()) {
            return;
        }
        if (this.options.output?.breakdown?.listIcon !== undefined) {
            this.insertCSS(".bc__output__breakdown::before{ content: '\\" + this.options.output.breakdown.listIcon + "'; font-family: 'Font Awesome 6 Pro'; font-weight: 300; position: absolute; left: 0px; top: 4px; color: var(--bc-accent); }");
        }
        else {
            this.insertCSS(".bc__output__breakdown::before{ content: '\\f058'; font-family: 'Font Awesome 6 Pro'; font-weight: 300; position: absolute; left: 0px; top: 4px; color: var(--bc-accent); }");
        }
        let mainHeading = document.createElement("h2");
        mainHeading.innerText = (this.options.heading !== undefined ? this.options.heading : "Calculate your Budget");
        root.appendChild(mainHeading);
        let mainDiv = document.createElement("div");
        mainDiv.classList.add("row", "m-0", "justify-content-between", "align-items-start");
        root.appendChild(mainDiv);
        if (!this.generateInput(mainDiv) || !this.generateOutput(mainDiv)) {
            return;
        }
        this.progressScript();
    }
    generateOutput(mainDiv) {
        let bc__output = this.createDiv(mainDiv, "col-lg-4", "col-12", "bc__output", "text-center");
        let bc__output__heading = this.createDiv(bc__output, "bc__output__heading");
        bc__output__heading.innerText = (this.options.output?.heading?.text !== undefined ? this.options.output?.heading?.text : "Your Recommended");
        let bc__output__heading__colored = this.createSpan(bc__output__heading, (this.options.output?.heading?.colored !== undefined ? this.options.output?.heading?.colored : "Marketing Spend"), "colored");
        let bc__output__revenue = this.createDiv(bc__output, "bc__output__revenue");
        let bc__output__revenue__percentage = this.createSpan(bc__output__revenue, "", "bc__output__revenue__percentage");
        this.bc__output__revenue__percentage_span = this.createSpan(bc__output__revenue__percentage, "");
        bc__output__revenue__percentage.innerHTML += (this.options.output?.revenuePercentageLabel !== undefined ? this.options.output?.revenuePercentageLabel : "% of Revenue");
        this.bc__output__revenue__total = this.createSpan(bc__output__revenue, "", "bc__output__revenue__total");
        let bc__output__revenue__span = this.createSpan(bc__output__revenue, (this.options.output?.perMonthLabel !== undefined ? this.options.output?.perMonthLabel : "per month"));
        let perMonthTooltip_data = (this.options.output?.perMonthTooltip !== undefined ? this.options.output?.perMonthTooltip : {
            icon: "fa-light fa-circle-info",
            text: "This marketing budget could be allocated to salaries, specialist agency support and marketing initiatives, campaigns, ad spends and related expenses.",
            ariaLabel: "Open this tooltip to learn more about the Monthly Budget"
        });
        let perMonthTooltip = this.createToolTip(perMonthTooltip_data);
        bc__output__revenue__span.appendChild(perMonthTooltip);
        if (this.options.output?.breakdown !== undefined) {
            let bc__output__breakdowns = this.createDiv(bc__output, "bc__output__breakdowns");
            let bc__output__breakdowns_details = document.createElement("details");
            bc__output__breakdowns_details.addEventListener("toggle", function () {
                bc__output.classList.toggle("bc__details__open");
            });
            let bc__output__breakdowns_details_summary = document.createElement("summary");
            bc__output__breakdowns_details_summary.innerText = (this.options.output?.breakdown?.heading !== undefined ? this.options.output?.breakdown?.heading : "Example Breakdown");
            let bc__output__breakdowns_details_summary_icon = document.createElement("i");
            let icons = (this.options.output?.breakdown?.openIcon !== undefined ? [...this.options.output.breakdown.openIcon.split(' ')] : [..."fa-regular fa-chevron-down".split(' ')]);
            bc__output__breakdowns_details_summary_icon.classList.add(...icons);
            bc__output__breakdowns_details_summary.appendChild(bc__output__breakdowns_details_summary_icon);
            bc__output__breakdowns_details.appendChild(bc__output__breakdowns_details_summary);
            bc__output__breakdowns.appendChild(bc__output__breakdowns_details);
            let breakdown_ul = document.createElement("ul");
            bc__output__breakdowns_details.appendChild(breakdown_ul);
            let breakdowns = this.options.output?.breakdown?.breakdowns;
            if (breakdowns !== undefined) {
                breakdowns.forEach(breakdown => {
                    let breakdownEl = document.createElement("li");
                    breakdownEl.classList.add("bc__output__breakdown");
                    breakdownEl.setAttribute("data-percentage", breakdown.percentage.toString());
                    let bc__output__breakdown__name = this.createSpan(breakdownEl, breakdown.text, "bc__output__breakdown__name");
                    let bc__output__breakdown__subtotal = this.createSpan(breakdownEl, "(" + breakdown.percentage.toString() + "%) ", "bc__output__breakdown__subtotal");
                    let subtotalSpan = this.createSpan(bc__output__breakdown__subtotal, "");
                    breakdown_ul.appendChild(breakdownEl);
                });
            }
            else {
                mainDiv.remove();
                console.error("[BusinessCalculator] No breakdowns were defined!");
                return false;
            }
            let breakdown_hint = this.createSpan(bc__output__breakdowns_details, (this.options.output?.breakdown?.hint !== undefined ? this.options.output?.breakdown?.hint : "Adapt your budget based on campaign results and business goals, experimenting with tailored strategies and channels."), "bc__hint");
        }
        let bc__button = document.createElement("a");
        bc__button.href = (this.options.output?.button !== undefined ? this.options.output?.button.href : "#");
        bc__button.innerText = (this.options.output?.button !== undefined ? this.options.output?.button.text : "See plans");
        bc__button.classList.add("bc__button");
        bc__output.appendChild(bc__button);
        let bc__hint = this.createDiv(bc__output, "bc__hint", "reduced");
        bc__hint.innerHTML = (this.options.output?.endHint !== undefined ? this.options.output?.endHint : "Unsure where to allocate your marketing spend? <a href='#'>Take the quiz</a>");
        return true;
    }
    createDiv(parent, ...classes) {
        let element = document.createElement("div");
        if (classes.length !== 0)
            element.classList.add(...classes);
        if (parent != null)
            parent.appendChild(element);
        return element;
    }
    createSpan(parent, content, ...classes) {
        let element = document.createElement("span");
        if (classes.length !== 0)
            element.classList.add(...classes);
        element.innerHTML = content;
        if (parent != null)
            parent.appendChild(element);
        return element;
    }
    createSlider(parent, tooltip, heading, sliderOptions) {
        let bc__slider = this.createDiv(parent, "bc__slider");
        let bc__slider__upper = this.createDiv(bc__slider, "bc__slider__upper");
        let sliderTooltip = (tooltip !== undefined ? tooltip : {
            icon: "fa-light fa-circle-info",
            text: "Your business' average gross monthly revenue - total brought in before any expenses.",
            ariaLabel: "Open this tooltip to learn more about the Monthly Revenue"
        });
        let slider_subheading_text = (heading !== undefined ? heading : "Monthly Revenue");
        let slider_subheading = this.createSubHeading(sliderTooltip, slider_subheading_text);
        bc__slider__upper.appendChild(slider_subheading);
        this.bc__slider__revenue = this.createSpan(bc__slider__upper, "", "bc__slider__revenue", "subHeading");
        let bc__slider__middle = this.createDiv(bc__slider, "bc__slider__middle");
        let sliderMin = (sliderOptions?.min !== undefined ? sliderOptions?.min.toString() : "0");
        let sliderMax = (sliderOptions?.max !== undefined ? sliderOptions?.max.toString() : "1000000");
        let sliderStep = (sliderOptions?.step !== undefined ? sliderOptions?.step.toString() : "5000");
        this.bc__slider__input = document.createElement("input");
        this.bc__slider__input.type = "range";
        this.bc__slider__input.id = "revenue";
        this.bc__slider__input.name = "revenue";
        this.bc__slider__input.min = sliderMin;
        this.bc__slider__input.max = sliderMax;
        this.bc__slider__input.step = sliderStep;
        this.bc__slider__input.oninput = () => {
            this.progressScript(this);
        };
        this.bc__slider__input.value = ((parseInt(sliderMax) + parseInt(sliderMin)) / 2).toString();
        bc__slider__middle.appendChild(this.bc__slider__input);
        let bc__slider__lower = this.createDiv(bc__slider, "bc__slider__lower");
        let bc__slider__lower__lesser__label = this.createSpan(bc__slider__lower, (this.currencyOrient === BC_Currency_Orientation.BEFORE_TEXT ? this.currency + BusinessCalculator.formatMoney(sliderMin) : BusinessCalculator.formatMoney(sliderMin) + this.currency));
        let bc__slider__lower__higher__label = this.createSpan(bc__slider__lower, (this.currencyOrient === BC_Currency_Orientation.BEFORE_TEXT ? this.currency + BusinessCalculator.formatMoney(sliderMax) : BusinessCalculator.formatMoney(sliderMax) + this.currency));
    }
    generateInput(mainDiv) {
        let bc__input = this.createDiv(mainDiv, "col-lg-7", "col-12", "bc__input");
        this.createSlider(bc__input, this.options.input?.monthlyRevenue?.tooltip, this.options.input?.monthlyRevenue?.heading, this.options.input?.monthlyRevenue?.slider);
        let bc__industry__selector = this.createDiv(bc__input, "bc__industry__selector");
        let industry_subheading_text = (this.options.input?.industry?.heading !== undefined ? this.options.input?.industry?.heading : "Industry");
        let industryTooltip = (this.options.input?.industry?.tooltip !== undefined ? this.options.input?.industry?.tooltip : {
            icon: "fa-light fa-circle-info",
            text: "Select your business industry or closest match.",
            ariaLabel: "Open this tooltip to learn more about the Industries"
        });
        let industry_subheading = this.createSubHeading(industryTooltip, industry_subheading_text);
        bc__industry__selector.appendChild(industry_subheading);
        let bc__industry__types = this.createDiv(bc__industry__selector, "bc__industry__types");
        let industries = this.options.input?.industry?.industries;
        if (industries !== undefined) {
            let count = 0;
            industries.forEach(industry => {
                let industry_type = this.createSpan(bc__industry__types, industry.text, "bc__industry__type");
                if (0 == count++) {
                    industry_type.classList.add("active");
                }
                industry_type.setAttribute("data-percentage", industry.percentage.toString());
                industry_type.addEventListener("click", () => {
                    document.querySelectorAll("span.bc__industry__type").forEach(span => span.classList.remove("active"));
                    industry_type.classList.add("active");
                    this.revPercentage = parseInt(industry_type.getAttribute("data-percentage"));
                    this.updateTotal();
                });
            });
        }
        else {
            mainDiv.remove();
            console.error("[BusinessCalculator] No industries specified!");
            return false;
        }
        let bc__growth__goal = this.createDiv(bc__input, "bc__growth__goal");
        let growthGoal_subheading_text = (this.options.input?.growth?.heading !== undefined ? this.options.input?.growth?.heading : "Growth Goal");
        let growthGoal_tooltip = (this.options.input?.growth?.tooltip !== undefined ? this.options.input?.growth?.tooltip : {
            icon: "fa-light fa-circle-info",
            text: "Conservative: a more cautious approach, aiming for steady and sustainable growth. <br>Moderate: balanced growth, combining stability with some expansion. <br>Aggressive: rapid growth and expansion, even if it involves higher risk.",
            ariaLabel: "Open this tooltip to learn more about the Growth Goals"
        });
        let growth_subheading = this.createSubHeading(growthGoal_tooltip, growthGoal_subheading_text);
        bc__growth__goal.appendChild(growth_subheading);
        let bc__growth__goal__buttons = this.createDiv(bc__growth__goal, "bc__growth__goal__buttons");
        let growth_buttons = this.options.input?.growth?.buttons;
        if (growth_buttons !== undefined) {
            let count = 0;
            growth_buttons.forEach(button => {
                let growth_button = this.createSpan(bc__growth__goal__buttons, button.text, "bc__growth__goal__button");
                if (0 == count++) {
                    growth_button.classList.add("active");
                }
                growth_button.setAttribute("data-multiplier", button.multiplier.toString());
                growth_button.addEventListener("click", () => {
                    document.querySelectorAll("span.bc__growth__goal__button").forEach(span => span.classList.remove("active"));
                    growth_button.classList.add("active");
                    this.multiplier = parseFloat(growth_button.getAttribute("data-multiplier"));
                    this.updateTotal();
                });
            });
        }
        else {
            mainDiv.remove();
            console.error("[BusinessCalculator] No growth buttons specified!");
            return false;
        }
        return true;
    }
    progressScript(p0) {
        //console.log(this.bc__slider__input)
        const sliderValue = parseInt(this.bc__slider__input.value);
        let percentage = ((sliderValue - parseInt(this.bc__slider__input.min)) / (parseInt(this.bc__slider__input.max) - parseInt(this.bc__slider__input.min))) * 100;
        this.bc__slider__input.style.background = `linear-gradient(to right, var(--bc-primary) ${percentage}%, var(--bc-foreground-alternative) ${percentage}%)`;
        this.bc__slider__revenue.innerText = BusinessCalculator.formatMoney(sliderValue);
        this.updateTotal();
    }
    createSubHeading(tooltip, headingText) {
        let subheading = this.createSpan(null, headingText, "subheading");
        let tooltipEl = this.createToolTip(tooltip);
        subheading.appendChild(tooltipEl);
        return subheading;
    }
    updateTotal() {
        let totalMultiplier = (this.revPercentage * this.multiplier) / 100;
        this.bc__output__revenue__total.innerHTML = BusinessCalculator.formatMoney(parseInt(this.bc__slider__input.value) * totalMultiplier);
        document.querySelector(".bc__output__revenue__percentage>span").innerHTML = (Math.round(totalMultiplier * 1000) / 10).toString();
        document.querySelectorAll("li.bc__output__breakdown").forEach(breakdown => {
            let percentage = breakdown.getAttribute("data-percentage");
            let total = (parseInt(this.bc__slider__input.value) * totalMultiplier) * (parseFloat(percentage) / 100);
            breakdown.querySelector(".bc__output__breakdown__subtotal>span").innerHTML =
                (this.currencyOrient === BC_Currency_Orientation.BEFORE_TEXT
                    ? this.currency + BusinessCalculator.formatMoney(total)
                    : BusinessCalculator.formatMoney(total) + this.currency);
        });
    }
    static formatMoney(x) {
        return Math.floor(parseFloat(x)).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }
    createToolTip(tooltip) {
        let tooltipEl = this.createDiv(null, "bc__tooltip");
        tooltipEl.ariaLabel = tooltip.ariaLabel;
        let iconWrapper = this.createDiv(tooltipEl, "icon");
        let icon = document.createElement("i");
        icon.classList.add(...tooltip.icon.split(' '));
        iconWrapper.appendChild(icon);
        let tooltipContent = this.createDiv(icon, "content");
        let tooltipInner = this.createDiv(tooltipContent, "inner");
        tooltipInner.innerHTML = tooltip.text;
        return tooltipEl;
    }
    createGlobalCSS() {
        this.globalCSS = document.createElement('style');
        document.head.appendChild(this.globalCSS);
    }
    insertColors() {
        /* defaults:
        :root{
            --bc-background: #060E15;
            --bc-foreground: #E9EEEB;
            --bc-foreground-alternative: #818181;
            --bc-primary: #0C92A8;
            --bc-secondary: #327773;
            --bc-accent: #6EBDC1;
        }
        */
        this.insertCSS(":root { --bc-background: " + (this.options.colors?.background ? this.options.colors?.background : "#060E15") + "; --bc-foreground: " + (this.options.colors?.foreground ? this.options.colors?.foreground : "#E9EEEB") + "; --bc-foreground-alternative: " + (this.options.colors?.foregroundAlternative ? this.options.colors?.foregroundAlternative : "#818181") + "; --bc-primary: " + (this.options.colors?.primary ? this.options.colors?.primary : "#0C92A8") + "; --bc-secondary: " + (this.options.colors?.secondary ? this.options.colors?.secondary : "#327773") + "; --bc-accent: " + (this.options.colors?.third ? this.options.colors?.third : "#6EBDC1") + ";}");
    }
    insertCSS(rule) {
        this.globalCSS.sheet?.insertRule(rule);
    }
}
var BC_Currency_Orientation;
(function (BC_Currency_Orientation) {
    BC_Currency_Orientation[BC_Currency_Orientation["BEFORE_TEXT"] = 0] = "BEFORE_TEXT";
    BC_Currency_Orientation[BC_Currency_Orientation["AFTER_TEXT"] = 1] = "AFTER_TEXT";
})(BC_Currency_Orientation || (BC_Currency_Orientation = {}));
