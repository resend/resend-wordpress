jQuery(function ($) {
	const $resendAlerts = $("#resend_alerts");
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
		message = message || "Unknown response";

		const alertClass = success ? "is-success" : "is-danger";
		const $alertIcon = success
			? $(
					`<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>`
			  )
			: $(
					`<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>`
			  );

		const $alert = $(`
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
		const $form = $(this);
		const $button = $form.find('[type="submit"]');
		setButtonLoading($button, "Saving...");
		$.post(
			resendAjax.ajax_url,
			{
				action: "resend_enter_key",
				_wpnonce: resendAjax.nonce,
				key: $form.find("#resend_api_key").val(),
			},
			function (response) {
				const type = response.data?.type;
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
		const $form = $(this);
		const $button = $form.find('[type="submit"]');
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

	// Onboarding

	function resendCompleteKeyStep() {
		const $createKeyStep = $(".resend-setup-step-create-key");
		const $enterKeyStep = $(".resend-setup-step-enter-key");

		const $createKeyStepAction = $createKeyStep.find(
			".resend-setup-steps-actions"
		);
		const $enterKeyStepAction = $enterKeyStep.find(".resend-button");

		$createKeyStep.addClass("is-complete");
		$createKeyStepAction.remove();

		$enterKeyStep.removeClass("is-disabled");
		$enterKeyStepAction.addClass("is-primary");
	}

	$("#resend-use-existing-key").on("click", function (e) {
		e.preventDefault();
		const $enterKeyStep = $(".resend-setup-step-enter-key");
		resendCompleteKeyStep();
		$enterKeyStep.find(".resend-input").focus();
	});

	$("#resend-create-key").on("click", function (e) {
		setTimeout(function () {
			resendCompleteKeyStep();
		}, 500);
	});

	// Password Toggle

	function resendTogglePassword(element, inputId) {
		const $input = $("#" + inputId);

		const $element = $(element);
		const $showIcon = $element.find("#show-password");
		const $hideIcon = $element.find("#hide-password");

		if ($input.attr("type") === "password") {
			$input.attr("type", "text");
			$showIcon.css("display", "none");
			$hideIcon.css("display", "inline-flex");
		} else {
			$input.attr("type", "password");
			$showIcon.css("display", "inline-flex");
			$hideIcon.css("display", "none");
		}
	}
});
