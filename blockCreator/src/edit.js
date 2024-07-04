/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-i18n/
 */
import { __ } from "@wordpress/i18n";

/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-block-editor/#useblockprops
 */
import { InspectorControls, useBlockProps } from "@wordpress/block-editor";
import { PanelBody, TextControl, ToggleControl } from "@wordpress/components";

/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import "./editor.scss";

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#edit
 *
 * @return {Element} Element to render.
 */

function sanitizeTextField(text) {
	// Trim leading and trailing whitespace

	let sanitized = "";
	// Remove HTML tags
	for (let i = 0; i < text.length; i++) {
		if (text[i] === "<") {
			while (text[i] !== ">" && i < text.length) {
				i++;
			}
		}
		if (
			[
				"a",
				"b",
				"c",
				"d",
				"e",
				"f",
				"g",
				"h",
				"i",
				"j",
				"k",
				"l",
				"m",
				"n",
				"o",
				"p",
				"q",
				"r",
				"s",
				"t",
				"u",
				"v",
				"w",
				"x",
				"y",
				"z",
				"A",
				"B",
				"C",
				"D",
				"E",
				"F",
				"G",
				"H",
				"I",
				"J",
				"K",
				"L",
				"M",
				"N",
				"O",
				"P",
				"Q",
				"R",
				"S",
				"T",
				"U",
				"V",
				"W",
				"X",
				"Y",
				"Z",
				"1",
				"2",
				"3",
				"4",
				"5",
				"6",
				"7",
				"8",
				"9",
				"0",
				"é",
				"è",
				",",
				"ç",
				"à",
				"ù",
				".",
				"ô",
				"î",
				"ê",
				"û",
				"ö",
				"ï",
				"ë",
				"ü",
				"â",
				"ä",
				"€",
				"$",
				"£",
				"¥",
				"%",
				"&",
				"Â",
				"Ä",
				"Ê",
				"Ë",
				"Î",
				"Ï",
				"Ö",
				"Ô",
				"Û",
				"Ü",
				"Ù",
				"À",
				"Ç",
				"É",
				"È",
				"Æ",
				"Œ",
				"œ",
				"Å",
				"Ø",
				"Þ",
				"ð",
				"Ý",
				"ý",
				"þ",
				"ÿ",
				"ß",
				"Ÿ",
				"Š",
				"š",
				"Ž",
				"ž",
				" ",
			].includes(text[i])
		) {
			sanitized += text[i];
		}
	}

	return sanitized;
}

export default function Edit({ attributes, setAttributes }) {
	const {
		productName,
		productPrice,
		variationNumber,
		variationName,
		variations,
	} = attributes;

	// Generate TextControls for variations
	const variationControls = [];
	// console.log(variations);
	// if (
	// 	variationNumber > 0 &&
	// 	(variations == undefined || variations.length !== variationNumber)
	// ) {
	// 	setAttributes({
	// 		variations: Array.from({ length: variationNumber }, () => ["", ""]),
	// 	});
	// }
	if (
		(!variations || variations.length != variationNumber) &&
		variationNumber > 0
	) {
		let nArray = [];
		if (variations) {
			nArray = variations;
		}
		nArray.push(["", ""]);
		setAttributes({ variations: nArray });
	}

	for (let i = 0; i < variationNumber; i++) {
		variationControls.push(
			<div style={{ display: "flex", justifyContent: "space-between" }}>
				<TextControl
					className="variations"
					label={__(
						"Variation " + (i + 1),
						"wp-ultra-simple-paypal-shopping-cart",
					)}
					value={variations && variations[i] ? variations[i][0] : ""}
					onChange={(value) => {
						// Clone the variations array to create a new reference
						let newArray = variations.map((variation, index) => {
							if (index === i) {
								// For the current index, return a new array with the updated value
								return [sanitizeTextField(value), ...variation.slice(1)];
							}
							return variation;
						});

						// Update the state with the new array
						setAttributes({ variations: newArray });
					}}
				/>
				<TextControl
					className="variations"
					label={__("Price " + (i + 1), "wp-ultra-simple-paypal-shopping-cart")}
					value={variations && variations[i] ? variations[i][1] : ""}
					onChange={(value) => {
						// Clone the variations array to create a new reference
						let newArray = variations.map((variation, index) => {
							if (index === i) {
								// For the current index, return a new array with the updated value
								return [variation[0], sanitizeTextField(value)];
							}
							return variation;
						});

						// Update the state with the new array
						setAttributes({ variations: newArray });
					}}
				/>
			</div>,
		);
	}

	return (
		<>
			<div {...useBlockProps()}>
				<div
					id="productInfo"
					style={{ display: "flex", flexDirection: "column" }}
				>
					<div
						className="product-details"
						style={{
							display: "flex",
							justifyContent: "space-between",
							marginBottom: "10px",
						}}
					>
						<div style={{ paddingRight: "10px" }}>
							<TextControl
								className="productTag"
								label={__(
									"Product name",
									"wp-ultra-simple-paypal-shopping-cart",
								)}
								value={productName || ""}
								onChange={(value) =>
									setAttributes({ productName: sanitizeTextField(value) })
								}
							/>
						</div>
					</div>
					<div
						className="product-details"
						style={{ display: "flex", flexDirection: "column" }}
					>
						<TextControl
							className="variationTag"
							label={__(
								"Name of variation",
								"wp-ultra-simple-paypal-shopping-cart",
							)}
							value={variationName || ""}
							onChange={(value) => {
								const newAttributes = {
									variationName: sanitizeTextField(value),
								};
								setAttributes(newAttributes);
							}}
						/>
						<TextControl
							className="variationTag"
							label={__(
								"Number of variations",
								"wp-ultra-simple-paypal-shopping-cart",
							)}
							type="number"
							value={variationNumber || ""}
							onChange={(value) => {
								let newAttributes = {
									variationNumber: sanitizeTextField(value),
								};
								setAttributes(newAttributes);
							}}
						/>
					</div>
					<div
						style={{
							display: "flex",
							flexDirection: "column",
						}}
					>
						{variationControls}
					</div>
				</div>
			</div>
		</>
	);
}

// export function save() {
// 	return null;
// }
