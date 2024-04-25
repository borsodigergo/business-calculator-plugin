# Business Calculator

This WordPress plugin is an easy-to-use, easy-to-install marketing-calculator. After its activation, it can be displayed on any wp-preprocessed page with the shortcode `[business-calculator]`. or by calling the `do_shortcode()` php-directive. 
**The Minimum required PHP version is 8.0**.

This plugin has the following dependencies, all of which are packed with the plugin:
- FontAwesome 6 Pro or later (css + webfonts)
- Bootstrap 5.0.2 (js+css)

The plugin is basically a wp-wrapper for the original js library, also written by me. Its specification can be found below.

# \[JS-Lib\]

This is a lightweight, fast Vanilla-javascript based BusinessCalculator script, with fully customizable elements.

You can find a working demo on the [index.html](index.html) file.

## Usage
The calculator has one dependency, which is the **FontAwesome** iconset. You can specify with a flag if you want to use the **Pro**, or the **Free version**. Regardless, the CSS file/font files for FontAwesome has to be included in order for the icons to display correctly.

To use this calculator, you have to include/link two files above the dependency: the `.js` and the `.css` file:

```html
<head>
    ...
    <link rel="stylesheet" href="css/calculator.min.css">
    ...
</head>
<body>
...
    <script src="js/calculator.min.js"></script>
</body>
```
### Quick start
The calculator can be brought to life by initializing a `BusinessCalculator` object with the minimal reguired data, and then by calling its `init()` function:
```js
const bc = new BusinessCalculator({
    root: document.querySelector("rootElement"),
    input: {
        industry: {
            industries: [
                {
                    text: "Industry1",
                    percentage: 10
                },
                {
                    text: "Industry2",
                    percentage: 20
                },
                {
                    text: "Industry3",
                    percentage: 30
                }
            ]
        },
        growth: {
            buttons: [
                {
                    text: "Conservative",
                    multiplier: 1
                },
                {
                    text: "Moderate",
                    multiplier: 1.5
                }
            ]
        }
    }
});

bc.init()
```

> The options you can provide to the constructor have alot of non-required options, all of which are described in detail below. The absolute required are the `root`, the `industries` and the `growth>buttons` from the input. If any of these are not present, an error message will be thrown to the console, thus aborting the calculator's generator process

### Syntax and options

The constructor's syntax is as follows:

`const bc = new BusinessCalculator( <options> )`, where the `options` object has multiple key-value pairs, all of which are modifying the appearance and behaviour of the calculator.

<table>
    <tr>
        <th>Key</th><th>Type</th><th>Required</th><th>Default value</th><th>Description</th>
    </tr>
    <tr>
        <td>root</td>
        <td>HTMLElement (object)</td>
        <td>yes</td>
        <td>null</td>
        <td>The element, in which the calculator's HTML and structure will be generated.</td>
    </tr>
    <tr>
        <td>heading</td>
        <td>string</td>
        <td>no</td>
        <td>"Calculate your Budget"</td>
        <td>The H2 heading of the calculator</td>
    </tr>
    <tr>
        <td>currency</td>
        <td>string</td>
        <td>no</td>
        <td>"$"</td>
        <td>The currency symbol used by numeric money displays</td>
    </tr>
    <tr>
        <td>colors</td>
        <td>Object</td>
        <td>no</td>
        <td>
            <pre>
{
    primary: "#0C92A8",
    secondary: "#327773",
    third: "#6EBDC1",
    background: "#060E15",
    foreground: "#E9EEEB",
    foregroundAlternative: "#818181"
} 
            </pre>
        </td>
        <td>This object describes the colors used in the calculator.<br>Its possible properties are <code>primary`, <code>secondary</code>, <code>third</code>, <code>background</code>, <code>foreground</code>, and <code>foregroundAlternative</code>  <br><br>None of the properties are required, you can change selectively any of them.<br>If a color is not specified, the default will be used instead.</td>
    </tr>
    <tr>
        <td>input</td>
        <td>Object</td>
        <td>yes (partially)</td>
        <td>null</td>
        <td>This object defines all the input elements present on the calculator. This includes the industry buttons, the revenue slider, and the growth buttons. Below you can find all these in detail</td>
    </tr>
    <tr>
        <td>input: monthlyRevenue</td>
        <td>Object</td>
        <td>no</td>
        <td>
            <pre>
{
    heading: "Monthly Revenue",
    tooltip: {
        icon: "fa-light fa-circle-info",
        text: "Your business' avreage gross monthy revenue - total brought on befire any expenses",
        ariaLabel: "Open this tooltip to learn more about the Monthly Revenue"
    },
    slider: {
        min: 0,
        max: 1000000,
        step: 5000
    }
}           </pre>
        </td>
        <td>This object specifies the first section inside the inputs, the monthly revenue slider. All of its properties are optional, since all have a default value</td>
    </tr>
    <tr>
        <td>input: industry</td>
        <td>Object</td>
        <td>yes (partially)</td>
        <td>
            <pre>
{
    heading: "Industry", // not required
    tooltip: {
        icon: "fa-light fa-circle-info",
        text: "Select your business industry or closest match",
        ariaLabel: "Open this tooltip to learn more about the Industries"
    }, // not required
    industries: [] // required, not populated by default
}           </pre>
        </td>
        <td>This object specifies the possible industries one can choose from, when calculating its budget. They basically determmine, that which percent of the whole revenue is planned to be spent on marketing. <br>The object has one required property (hence the partial requirity), which is an array of objects in the following form:
            <pre>
[
    ..,
    {
        text: "Industry Display Text",
        percentage: 30 // this will be used for calculations
    },
    ..
]<br>
            </pre>
        </td>
    </tr>
    <tr>
        <td>input: growth</td>
        <td>Object</td>
        <td>yes (partially)</td>
        <td>
            <pre>
{
    heading: "Growth Goal", // not required
    tooltip: {
        icon: "fa-light fa-circle-info",
        text: "Conservative: a more cautious approach, aiming for steady and sustainable growth. Moderate:  balanced growth, combining stability with some expansion. Aggressive: rapid growth and expansion, even if it involves higher risk.",
        ariaLabel: "Open this tooltip to learn more about the Growth Goals"
    }, // not required
    buttons: [] // required
}           </pre>
        </td>
        <td>This object specifies the possible growth buttons, from which the user can choose. They are basically multipliers, with which the per month marketing budget is multiplied as the calculator's ending operation.<br>This object has one required property, which is an array of objects in the following form:<br>
        <pre>
[
    ..,
    {
        text: "Conservative",
        multiplier: 1
    },
    ..
]
        </pre>
        </td>
    </tr>
    <tr>
        <td>output</td>
        <td>Object</td>
        <td>no</td>
        <td>null</td>
        <td>This object defines all the output elements used by the calculator (we will refer to the area where these elements are displayed as the 'right column'). This includes the output heading, the texts and labels used in the right column, and the optional breakdown list</td>
    </tr>
    <tr>
        <td>output: heading</td>
        <td>Object</td>
        <td>no</td>
        <td>
            <pre>
{
    text: "Your Recommended",
    colored: "Marketing Spend"
}           </pre>
        </td>
        <td>This heading object determines the text displayed in the right column's top. The colored one has a linear gradient text color, constructed from the secondary and the third color</td>
    </tr>
    <tr>
        <td>output: revenuePercentageLabel</td>
        <td>string</td>
        <td>no</td>
        <td>
            "% of Revenue"
        </td>
        <td>The text that displays next to the displayed percentage (which is the percentage of the selected industry)</td>
    </tr>
    <tr>
        <td>output: perMonthLabel</td>
        <td>string</td>
        <td>no</td>
        <td>
            "per month"
        </td>
        <td>The text displayed under the total calculated amount</td>
    </tr>
    <tr>
        <td>output: perMonthTooltip</td>
        <td>Object</td>
        <td>no</td>
        <td>
            <pre>
{
    icon: "fa-light fa-circle-info",
    text: "This marketing budget could be allocated to salaries, specialist agency support and marketing initiatives, campaigns, ad spends and related expenses",
    ariaLabel: "Open this tooltip to learn more about the Monthly Budget"
}           </pre>
        </td>
        <td>Tooltip, which displays next to the <code>perMonth</code> label</td>
    </tr>
    <tr>
        <td>output: breakdown</td>
        <td>Object</td>
        <td>no</td>
        <td>
            <pre>
{
    heading: "Example Breakdown",
    openIcon: "fa-regular fa-chevron-down",
    listIcon: "f058",
    breakdowns: [],
    hint: "Adapt your budget based on campaign results and business goals, experimenting with tailored strategies and channels."
}           </pre>
        </td>
        <td>This object specifies the breakdown of the calculated marketing budget. The big difference here is the <b>listIcon's value</b>. Since that is displayed using css-pseudo code, the <code>listIcon</code> property must be given in unicode form (so that it can be used as <code>content: '\listIcon'</code>)<br><br>The breakdowns can be added by objects of the array <code>breakdowns</code>, in the following format:<br>
        <pre>
[
    ..,
    {
        text: "Breakdown1",
        percentage: 20
    },
    ..
]
    </pre>One breakdown means, that for the specified purpose (<code>text</code>) the specified percentage of the revenue (<code>percentage</code>) should be used.</td>
    </tr>
    <tr>
        <td>output: button</td>
        <td>Object</td>
        <td>no</td>
        <td>
            <pre>
{
    text: "See plans",
    href: "#",
}           </pre>
        </td>
        <td>The button appearing under the breakdowns' list</td>
    </tr>
    <tr>
        <td>output: endHint</td>
        <td>Object</td>
        <td>no</td>
        <td>
            "Unsure where to allocate your marketing spend? &lt;a href="#"&gt;Take the quiz&lt;/a&gt;"
        </td>
        <td>Little text appearing under the button</td>
    </tr>
</table>

### Typescript

If you want to use this calculator inside a typescript project, you can do so by using the `.ts` version of the calculator. The BC_Options interface describes the object you pass to the constructor (whose properties are detailed in the table above).
Typing informations for the typescript version:
```ts
interface BC_Options {
    root: HTMLElement | null
    heading?: string
    currency?: string
    currencyOrientation?: BC_Currency_Orientation
    colors?: {
        primary?: string
        secondary?: string
        third?: string
        background?: string
        foreground?: string
        foregroundAlternative?: string
    }
    input?: {
        monthlyRevenue?: {
            heading?: string
            tooltip?: BC_Tooltip
            slider?: BC_Slider
        }
        industry: {
            heading?: string
            tooltip?: BC_Tooltip
            industries: BC_Industry[]
        }
        growth: {
            heading?: string
            tooltip?: BC_Tooltip
            buttons: BC_Growth_Button[]
        }
    }
    output?: {
        heading?: {
            text?: string
            colored?: string
        }
        revenuePercentageLabel?: string
        perMonthLabel?: string
        perMonthTooltip?: BC_Tooltip
        breakdown?: {
            heading?: string
            openIcon?: string
            listIcon?: string
            breakdowns?: BC_Breakdown[]
            hint?: string
        }
        button?: BC_Button
        endHint?: string
    }
}
enum BC_Currency_Orientation{
    "BEFORE_TEXT",
    "AFTER_TEXT"
}
interface BC_Tooltip {
    icon: string
    text: string
    ariaLabel: string
}
interface BC_Slider {
    min?: number
    max?: number
    step?: number
}
interface BC_Industry {
    text: string
    percentage: number
}
interface BC_Growth_Button {
    text: string
    multiplier: number
}
interface BC_Breakdown {
    text: string
    percentage: number
}
interface BC_Button {
    text: string
    href: string
}
```



## Licensing

This calculator is under the GPL license. You can freely use this in open source projects, modify it on your own, or use it in your public webapps. Re-distributing however, is probihited, and in such case a legal action may be performed.

Borsodi Gergő (7digits) © 2024
