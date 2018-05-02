import java.io.FileWriter;
import java.io.BufferedWriter;

// Mover object
Bob bob;

// Spring object
Spring spring;
float mass;

Table table;

int iterations;

void setup() {
  iterations = 0;
  mass = 0.1;
  size(980, 720);
  // Create objects at starting position
  // Note third argument in Spring constructor is "rest length"
  spring = new Spring(width/2, 10, 100); 
  bob = new Bob(width/2, 100, mass);
  table = new Table();
  table.addColumn("mass", Table.FLOAT);
  table.addColumn("length", Table.FLOAT);
}

void draw() {
  background(255); 

  // Apply a gravity force to the bob
  PVector gravity = new PVector(0, 2);
  bob.applyForce(gravity);

  // Connect the bob to the spring (this calculates the force)
  spring.connect(bob);
  // Constrain spring distance between min and max
  //spring.constrainLength(bob,30,200);

  // Update bob
  bob.update();
  // If it's being dragged
  bob.drag(mouseX, mouseY);

  // Draw everything
  spring.displayLine(bob); // Draw a line between spring and bob
  bob.display(); 
  spring.display();
  
  //check if the bob is nearly to in rest
  if (bob.velocity.y < 0.0001 && bob.velocity.y > -0.0001 && bob.accAux < 0.001 && bob.accAux > -0.001 && iterations < 500) {
    save();
  }
}

//save the information and run another iteration if need to
void save() {
  println(bob.position.y - spring.anchor.y);
  println(spring.d);
  println(spring.stretch);
  TableRow row = table.addRow();
  row.setFloat("mass", bob.mass);
  row.setFloat("length", spring.len);
  saveTable(table, "data.csv");
  iterations++;
  if(iterations < 500){
    mass = random(10, 100);
    spring = new Spring(width/2, 10, 100); 
    bob = new Bob(width/2, 100, mass);
  }
}