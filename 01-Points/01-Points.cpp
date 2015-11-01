#include <iostream>
#include <string>
using namespace std;

int main() {
    string startingPoint;
    getline(cin, startingPoint);
    
    int commandX, commandY;
    int curNumber;
    int len = startingPoint.length();
    int numbers[4], sz;
    bool startedNumber;
    
    sz = 0;
    curNumber = 0;
    startedNumber = false;
    
    char cur;
    for (int i = 0; i < len; i++) {
        cur = startingPoint[i];
        if (cur >= '0' && cur <= '9') {
           curNumber = curNumber * 10 + cur - '0';
           startedNumber = true;
           }
        else if (startedNumber) { 
             numbers[sz++] = curNumber; 
             curNumber = 0; 
             startedNumber = false;
        }
    }
        
    if (startedNumber)
       numbers[sz++] = curNumber;
    
    int currentX, currentY;
    
    currentX = numbers[0];
    currentY = numbers[1];
    
    string command;
    cin >> command;
    
    char currentCommand;    
    int stepX, stepY;
    stepX = 1, stepY = 1;

    int commandSize = command.length();
    for (int i = 0; i < commandSize; i++)
        switch (command[i]) {
               case '<': currentX -= stepX; break;
               case '>': currentX += stepX; break;
               case 'V': currentY += stepY; break;
               case '^': currentY -= stepY; break;
               case '~': stepX *= -1; stepY *= -1; break;
               default: break;
        }
    
    cout << "(" << currentX << ", " << currentY << ")" << endl;
    return 0;   
}
