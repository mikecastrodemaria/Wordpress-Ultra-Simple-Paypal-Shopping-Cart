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

import { sanitizeTextField } from "./../../js/utils.js";

export default function Edit({ attributes, setAttributes }) {
	const { productName, variationNumber, variationName, variations } =
		attributes;

	// Generate TextControls for variations
	const variationControls = [];

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
			<div className="text-control-two-container">
				<div className="text-control-wrapper">
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
				</div>
				<div className="text-control-wrapper">
					<TextControl
						className="variations"
						label={__(
							"Price " + (i + 1),
							"wp-ultra-simple-paypal-shopping-cart",
						)}
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
				</div>
			</div>,
		);
	}

	return (
		<>
			<div {...useBlockProps()}>
				<div className="product-details">
					<div className="text-control-wrapper">
						<TextControl
							className="productTag"
							label={__("Product name", "wp-ultra-simple-paypal-shopping-cart")}
							value={productName || ""}
							onChange={(value) =>
								setAttributes({ productName: sanitizeTextField(value) })
							}
						/>
					</div>
				</div>
				<div className="product-details">
					<div className="text-control-wrapper">
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
					</div>
					<div className="text-control-wrapper">
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
				</div>
				{variationControls}
			</div>
		</>
	);
}

// export function save() {
// 	return null;
// }
