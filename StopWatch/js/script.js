'use strict';
(function() {
	function seconds2time(seconds) {
		var hours = Math.floor(seconds / 3600);
		var minutes = Math.floor((seconds - (hours * 3600)) / 60);
		var seconds = seconds - (hours * 3600) - (minutes * 60);
		var time = "";

		if (hours != 0) {
			time = hours + ":";
		}
		if (minutes != 0 || time !== "") {
			minutes = (minutes < 10 && time !== "") ? "0" + minutes : String(minutes);
			time += minutes + ":";
		}
		if (time === "") {
			time = seconds + "s";
		} else {
			time += (seconds < 10) ? "0" + seconds : String(seconds);
		}
		return time;
	}

	function StopWatch() {
		this.elapsedTime = 0;
		this.timeId = null;
		this.timeDisplay = $('.time-display');
		this.startBtn = $('.start');
		this.pauseBtn = $('.pause');
		this.lapBtn = $('.lap');
		this.resetBtn = $('.reset');
		this.laps = [];
		this.bindEvents();
	}

	StopWatch.prototype.bindEvents = function() {
		this.startBtn.on('click', this.start.bind(this));
		this.pauseBtn.on('click', this.pause.bind(this));
		this.lapBtn.on('click', this.addLap.bind(this));
		this.resetBtn.on('click', this.reset.bind(this));
	};

	StopWatch.prototype.drawTime = function() {
		this.timeDisplay.html(seconds2time(this.elapsedTime));
	};

	StopWatch.prototype.start = function() {
		if (this.timeId) {
			return;
		}

		var _this = this;
		var lastRememberTime = (new Date()).getTime();
		this.timeId = setInterval(function() {
			var nextTime = (new Date()).getTime();
			_this.elapsedTime += (nextTime - lastRememberTime);
			lastRememberTime = nextTime;
			_this.drawTime();
		}, 16);
	};

	StopWatch.prototype.pause = function() {
		clearInterval(this.timeId);
		this.timeId = null;
	};

	StopWatch.prototype.reset = function() {
		this.pause();
		this.elapsedTime = 0;
		$('.lap-display').remove();
		this.laps = [];
		this.timeDisplay.html(' ');
	};

	StopWatch.prototype.addLap = function() {
		this.laps.push(this.elapsedTime);
		this.drawLap();
	};

	StopWatch.prototype.drawLap = function() {
		if (this.elapsedTime > 0) {
			var lapNode = $('<p class="bg-success lap-display"></p>');
			var lapTime = seconds2time(this.elapsedTime) + '<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>';
			lapNode.html(lapTime);
			$('.timer').append(lapNode);
		}
		this.laps.forEach(this.removeLaps());
	};

	StopWatch.prototype.removeLaps = function() {
		$('.glyphicon-remove').on('click', function(event) {
			var eventTarget = $(event.target);
			eventTarget.parent().remove();
		});
	};

	window.StopWatch = StopWatch;
})();

$(document).ready(function() {
	new StopWatch();
});