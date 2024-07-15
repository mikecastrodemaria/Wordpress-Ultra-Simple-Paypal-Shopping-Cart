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
	const {
		productName,
		productPrice,
		variation1Number,
		variation1Name,
		variations1,
		variation2Number,
		variation2Name,
		variations2,
	} = attributes;
	const variationControls1 = [];
	if (!variations1) {
		let nArray = [];
		if (variation1Number > 0) {
			for (let i = 0; i < variation1Number; i++) {
				nArray.push("");
			}
		}
		setAttributes({ variations1: nArray });
	}

	if (
		variations1 &&
		variations1.length < variation1Number &&
		variation1Number > 0
	) {
		let nArray = [];
		if (Array.isArray(variations1)) {
			nArray = variations1;
		}
		while (nArray.length < variation1Number) {
			nArray.push("");
		}

		setAttributes({ variations1: nArray });
	}
	if (
		variations1 &&
		variations1.length > variation1Number &&
		variation1Number > 0
	) {
		let nArray = [];
		nArray = variations1;
		while (nArray.length > variation1Number) {
			nArray.pop();
		}
		setAttributes({ variations1: nArray });
	}

	for (let i = 0; i < variation1Number - (variation1Number % 2); i++) {
		variationControls1.push(
			<div className="text-control-two-container">
				<div className="text-control-wrapper">
					<TextControl
						className="variations1"
						label={__(
							"Variation " + (i + 1),
							"wp-ultra-simple-paypal-shopping-cart",
						)}
						value={variations1 && variations1[i] ? variations1[i] : ""}
						onChange={(value) => {
							// Clone the variations1 array to create a new reference
							let newArray = variations1.map((variation, index) => {
								if (index === i - 1) {
									// For the current index, return a new array with the updated value
									return sanitizeTextField(value);
								}
								return variation;
							});

							// Update the state with the new array
							setAttributes({ variations1: newArray });
						}}
					/>
				</div>
				<div className="text-control-wrapper">
					<TextControl
						className="variations1"
						label={__(
							"Variation " + (i + 2),
							"wp-ultra-simple-paypal-shopping-cart",
						)}
						value={variations1 && variations1[i + 1] ? variations1[i + 1] : ""}
						onChange={(value) => {
							// Clone the variations1 array to create a new reference

							let newArray = variations1.map((variation, index) => {
								if (index === i) {
									// For the current index, return a new array with the updated value
									return sanitizeTextField(value);
								}
								return variation;
							});

							// Update the state with the new array
							setAttributes({ variations1: newArray });
						}}
					/>
				</div>
			</div>,
		);
		i++;
	}
	if (variationControls1.length * 2 < variation1Number) {
		const i = variationControls1.length * 2;
		variationControls1.push(
			<div className="text-control-two-container">
				<div className="text-control-wrapper">
					<TextControl
						className="variations1"
						label={__(
							"Variation " + (i + 1),
							"wp-ultra-simple-paypal-shopping-cart",
						)}
						value={variations1 && variations1[i] ? variations1[i] : ""}
						onChange={(value) => {
							// Clone the variations1 array to create a new reference
							let newArray = variations1.map((variation, index) => {
								if (index === i) {
									// For the current index, return a new array with the updated value
									return sanitizeTextField(value);
								}
								return variation;
							});

							// Update the state with the new array
							setAttributes({ variations1: newArray });
						}}
					/>
				</div>
			</div>,
		);
	}

	const variationControls2 = [];
	if (!variations2) {
		let nArray = [];
		if (variation2Number > 0) {
			for (let i = 0; i < variation2Number; i++) {
				nArray.push("");
			}
		}
		setAttributes({ variations2: nArray });
	}

	if (
		variations2 &&
		variations2.length < variation2Number &&
		variation2Number > 0
	) {
		let nArray = [];
		if (Array.isArray(variations2)) {
			nArray = variations2;
		}
		while (nArray.length < variation2Number) {
			nArray.push("");
		}

		setAttributes({ variations2: nArray });
	}
	if (
		variations2 &&
		variations2.length > variation2Number &&
		variation2Number > 0
	) {
		let nArray = [];
		nArray = variations2;
		while (nArray.length > variation2Number) {
			nArray.pop();
		}
		setAttributes({ variations2: nArray });
	}

	for (let i = 0; i < variation2Number - (variation2Number % 2); i++) {
		variationControls2.push(
			<div className="text-control-two-container">
				<div className="text-control-wrapper">
					<TextControl
						className="variations2"
						label={__(
							"Variation " + (i + 1),
							"wp-ultra-simple-paypal-shopping-cart",
						)}
						value={variations2 && variations2[i] ? variations2[i] : ""}
						onChange={(value) => {
							// Clone the variations2 array to create a new reference
							let newArray = variations2.map((variation, index) => {
								if (index === i - 1) {
									// For the current index, return a new array with the updated value
									return sanitizeTextField(value);
								}
								return variation;
							});

							// Update the state with the new array
							setAttributes({ variations2: newArray });
						}}
					/>
				</div>
				<div className="text-control-wrapper">
					<TextControl
						className="variations2"
						label={__(
							"Variation " + (i + 2),
							"wp-ultra-simple-paypal-shopping-cart",
						)}
						value={variations2 && variations2[i + 1] ? variations2[i + 1] : ""}
						onChange={(value) => {
							// Clone the variations2 array to create a new reference

							let newArray = variations2.map((variation, index) => {
								if (index === i) {
									// For the current index, return a new array with the updated value
									return sanitizeTextField(value);
								}
								return variation;
							});

							// Update the state with the new array
							setAttributes({ variations2: newArray });
						}}
					/>
				</div>
			</div>,
		);
		i++;
	}

	if (variationControls2.length * 2 < variation2Number) {
		const i = variationControls2.length * 2;
		variationControls2.push(
			<div className="text-control-two-container">
				<div className="text-control-wrapper">
					<TextControl
						className="variations2"
						label={__(
							"Variation " + (i + 1),
							"wp-ultra-simple-paypal-shopping-cart",
						)}
						value={variations2 && variations2[i] ? variations2[i] : ""}
						onChange={(value) => {
							// Clone the variations2 array to create a new reference
							let newArray = variations2.map((variation, index) => {
								if (index === i) {
									// For the current index, return a new array with the updated value
									return sanitizeTextField(value);
								}
								return variation;
							});

							// Update the state with the new array
							setAttributes({ variations2: newArray });
						}}
					/>
				</div>
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
					<div className="product-details">
						<div className="text-control-wrapper">
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
						<div className="text-control-wrapper">
							<TextControl
								className="productTag"
								label={__(
									"Product price",
									"wp-ultra-simple-paypal-shopping-cart",
								)}
								value={productPrice || ""}
								onChange={(value) =>
									setAttributes({ productPrice: sanitizeTextField(value) })
								}
							/>
						</div>
					</div>
					<div className="product-details">
						<div className="text-control-wrapper">
							<TextControl
								className="variationTag"
								label={__(
									"Name of the first variation",
									"wp-ultra-simple-paypal-shopping-cart",
								)}
								value={variation1Name || ""}
								onChange={(value) => {
									const newAttributes = {
										variation1Name: sanitizeTextField(value),
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
								value={variation1Number || ""}
								onChange={(value) => {
									const newAttributes = {
										variation1Number: sanitizeTextField(value),
									};
									setAttributes(newAttributes);
								}}
							/>
						</div>
					</div>
					<div
						className="varitaion-controls-container"
						style={{
							display: "flex",
							flexDirection: "column",
						}}
					>
						{variationControls1}
					</div>
					<div className="product-details">
						<div className="text-control-wrapper">
							<TextControl
								className="variationTag"
								label={__(
									"Name of the second variation",
									"wp-ultra-simple-paypal-shopping-cart",
								)}
								value={variation2Name || ""}
								onChange={(value) => {
									const newAttributes = {
										variation2Name: sanitizeTextField(value),
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
								value={variation2Number || ""}
								onChange={(value) => {
									const newAttributes = {
										variation2Number: sanitizeTextField(value),
									};
									setAttributes(newAttributes);
								}}
							/>
						</div>
					</div>
					<div
						className="varitaion-controls-container"
						style={{
							display: "flex",
							flexDirection: "column",
						}}
					>
						{variationControls2}
					</div>
				</div>
			</div>
		</>
	);
}
