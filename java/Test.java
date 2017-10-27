import java.util.*;

public class Test {

	public static void main(String[] args) {

		
		boolean flag = true;
		boolean tes = false;

		int a = flag ? 1 : (tes ? 2 : 3);

		System.out.println(a);

		System.exit(0);



		// System.exit();

		// double x = 1000000.0 / 3.0;

		// System.out.printf("%4.6f", x);	


		// String dir = System.getProperty("user.dir");

		// System.out.println(dir);

		Scanner in = new Scanner(System.in);

		System.out.print("需要多少退休金: ");
		Double goal = in.nextDouble();

		System.out.print("每年存入金额: ");
		Double money = in.nextDouble();

		System.out.print("存款利率: ");
		Double lixi = in.nextDouble();



		int year = 0;
		Double blance = 0.0;

		// while (blance < goal) {
			// blance += money;
			// blance += blance * lixi;
			// year++;
		// }

		// System.out.printf("需要 %d 年才能退休", year);

		String step;
		do {

			blance += money;
			blance += blance * lixi;

			year++;

			System.out.printf("已经存了 %d 年了, 存款是 %g %n", year, blance);

			System.out.print("继续存请输入Y, 退出请输入N :");
			step = in.next();

		} while(step.equals("Y"));

		
	}

}
