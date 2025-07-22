jQuery(function ($) {
	var $resendAlerts = $("#resend_alerts");
	if ($resendAlerts.data("message") && $resendAlerts.data("success")) {
		displayAlert($resendAlerts.data("message"), $resendAlerts.data("success"));
	}

	function setButtonLoading($button, loadingText) {
		$button.prop("disabled", true);
	}

	function resetButton($button) {
		$button.prop("disabled", false);
	}

	function displayAlert(message = "Unknown response", success = true) {
		var message = message || "Unknown response";
		var alertClass = success ? "is-success" : "is-danger";

		var $alertIcon = success
			? $(
					`<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>`
			  )
			: $(
					`<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>`
			  );

		var $alert = $(`
			<div class="resend-alert ${alertClass}">
				<span class="resend-alert-icon"></span>
				<p class="resend-alert-text">${message}</p>
			</div>
		`);

		$alert.find(".resend-alert-icon").html($alertIcon);
		$resendAlerts.html($alert);
	}

	$("#resend-api-key-form").on("submit", function (e) {
		e.preventDefault();
		var $form = $(this);
		var $button = $form.find('[type="submit"]');
		setButtonLoading($button, "Saving...");
		$.post(
			resendAjax.ajax_url,
			{
				action: "resend_enter_key",
				_wpnonce: resendAjax.nonce,
				key: $form.find("#resend_api_key").val(),
			},
			function (response) {
				var type = response.data?.type;
				if (type === "new-key-valid") {
					window.location.href = resendAjax.resend_url + "&status=connected";
				} else {
					displayAlert(response.data?.message, response.success);
					resetButton($button);
				}
			}
		);
	});

	$("#resend-test-email-form").on("submit", function (e) {
		e.preventDefault();
		var $form = $(this);
		var $button = $form.find('[type="submit"]');
		setButtonLoading($button, "Sending...");
		$.post(
			resendAjax.ajax_url,
			{
				action: "resend_send_test",
				_wpnonce: resendAjax.nonce,
				email: $form.find("#test_email").val(),
			},
			function (response) {
				displayAlert(response.data?.message, response.success);
			}
		).always(function () {
			resetButton($button);
		});
	});
});

function resendCreateKey() {
	setTimeout(function () {
		resendCompleteKeyStep();
	}, 500);
}

function resendUseExistingKey() {
	setTimeout(function () {
		const enterKeyStep = document.querySelector(".resend-setup-step-enter-key");

		resendCompleteKeyStep();

		const enterKeyInput = enterKeyStep.querySelector(".resend-input");
		enterKeyInput.focus();
	}, 100);
}

function resendCompleteKeyStep() {
	const createKeyStep = document.querySelector(".resend-setup-step-create-key");
	const enterKeyStep = document.querySelector(".resend-setup-step-enter-key");

	const createKeyStepAction = createKeyStep.querySelector(
		".resend-setup-steps-actions"
	);
	const enterKeyStepAction = enterKeyStep.querySelector(".resend-button");

	createKeyStep.classList.add("is-complete");
	createKeyStepAction.remove();

	enterKeyStep.classList.remove("is-disabled");
	enterKeyStepAction.classList.add("is-primary");
}

function resendTogglePassword(element, inputId) {
	const input = document.getElementById(inputId);

	const showIcon = element.querySelector("#show-password");
	const hideIcon = element.querySelector("#hide-password");

	if (input.type === "password") {
		input.type = "text";
		showIcon.style.display = "none";
		hideIcon.style.display = "inline-flex";
	} else {
		input.type = "password";
		showIcon.style.display = "inline-flex";
		hideIcon.style.display = "none";
	}
}
