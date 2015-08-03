QUnit.module('WeekTimerLine');
QUnit.test('setLine()', function() {
    expect(6+7+7);

    var wl = new WeekTimerLine();
    strictEqual(wl.t1_dow, 0);
    strictEqual(wl.t1_h,   0);
    strictEqual(wl.t1_m,   0);
    strictEqual(wl.t2_dow, 0);
    strictEqual(wl.t2_h,   0);
    strictEqual(wl.t2_m,   0);

    ok(wl.setLine('1:10:00-1:11:00'));
    strictEqual(wl.t1_dow, 1);
    strictEqual(wl.t1_h,  10);
    strictEqual(wl.t1_m,   0);
    strictEqual(wl.t2_dow, 1);
    strictEqual(wl.t2_h,  11);
    strictEqual(wl.t2_m,   0);

    ok(wl.setLine('9:12:30-9:13:40'));
    strictEqual(wl.t1_dow, 9);
    strictEqual(wl.t1_h,  12);
    strictEqual(wl.t1_m,  30);
    strictEqual(wl.t2_dow, 9);
    strictEqual(wl.t2_h,  13);
    strictEqual(wl.t2_m,  40);
});

QUnit.test('timeSinceWeekStart()', function() {
    expect(4+2);
    var wl = new WeekTimerLine();

    strictEqual(wl.timeSinceWeekStart(1, 4, 50), (1*24*60)+(4*60)+50); 

    strictEqual(wl.timeSinceWeekStart(1, 0,  0), (1*24*60));
    strictEqual(wl.timeSinceWeekStart(1, 6,  0), (1*24*60)+(6*60)); 
    strictEqual(wl.timeSinceWeekStart(1, 9,  0), (1*24*60)+(9*60));
    strictEqual(wl.timeSinceWeekStart(1, 9, 30), (1*24*60)+(9*60)+30);

    strictEqual(wl.timeSinceWeekStart(5, 9, 30), (5*24*60)+(9*60)+30);

});

QUnit.test('isON()', function() {
    expect(6+6+6);
    var wl = new WeekTimerLine();
    ok(wl.setLine('1:10:00-1:11:00'));
    ok(!wl.isON(1, 9, 0));
    ok(!wl.isON(1, 9,59));
    ok( wl.isON(1,10, 0));
    ok( wl.isON(1,10,59));
    ok(!wl.isON(1,11, 0));

    ok(wl.setLine('4:22:30-4:23:10'));
    ok(!wl.isON(4,22, 0));
    ok(!wl.isON(4,22,29));
    ok( wl.isON(4,22,30));
    ok( wl.isON(4,23, 9));
    ok(!wl.isON(4,23,10));

    ok(wl.setLine('3:23:30-4:01:10'));
    ok(!wl.isON(3,23, 0));
    ok(!wl.isON(3,23,29));
    ok( wl.isON(3,23,30));
    ok( wl.isON(4, 1, 9));
    ok(!wl.isON(4, 1,10));
    //for (d = 2; d <= 7; d++) { }
});



QUnit.module('WeekTimer');
QUnit.test('setLine()', function() {
    expect(2);

    var wt = new WeekTimer();
    var str = '7:12:32-7:23:34;1:01:00-1:02:00;4:11:30-5:12:00;';

    ok( wt.addNewTimers(str) );
    strictEqual(wt.getTimerString(), str);
});

QUnit.test('isON()', function() {
    expect(2+3+3);

    var wt = new WeekTimer();
    var str = '1:12:00-1:13:00;2:09:00-2:10:00;';

    ok( wt.addNewTimers(str) );
    strictEqual(wt.getTimerString(), str);

    ok(!wt.isON(1, 9,30) );
    ok( wt.isON(1,12,30) );
    ok(!wt.isON(1,13,30) );

    ok(!wt.isON(2, 8,30) );
    ok( wt.isON(2, 9,30) );
    ok(!wt.isON(2,10,30) );
});

QUnit.test('getWeekDayArray()', function() {
    expect(1);

    var wt = new WeekTimer();
    var str = '1:12:00-1:13:00;';

    ok( wt.addNewTimers(str) );
    var wda = wt.getWeekDayArray(1);

    wda.forEach(function(element, index, array) {
        console.log('wda[' + index + '] = ' + element.toString());
    });

});

